<?php

use KodeInfo\Utilities\Utils;
use KodeInfo\UserManagement\UserManagement;
use KodeInfo\Mailers\UsersMailer;

class AuthController extends BaseController
{

    public $userManager;
    public $mailer;

    function __construct(UserManagement $userManager,UsersMailer $mailer){
        $this->userManager = $userManager;
        $this->mailer = $mailer;
    }

    public function profile(){
        try {
            $this->data["user"] = User::findOrFail(Auth::user()->id);

            $this->data["countries"] = DB::table("countries")->remember(60)->get();
            $this->data['timezones'] = Config::get("timezones");

            return View::make('profile',$this->data);
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Session::flash("error_msg", "Account not found");
            return Redirect::to("/dashboard");
        }
    }

    public function storeProfile(){

        try
        {
            $user = User::findOrFail(Input::get("user_id"));
            $user->name = Input::get("name");
            $user->birthday = Input::get("birthday");
            $user->bio = Input::get("bio");
            $user->mobile_no = Input::get("mobile_no");
            $user->country = Input::get("country");
            $user->gender = Input::get("gender");
            $user->avatar = Input::hasFile('avatar')?Utils::imageUpload(Input::file('avatar'),'profile'):Input::get("old_avatar");
            $user->save();


            Session::flash("success_msg","Profile updated successfully");
            return Redirect::to("/profile");

        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            Session::flash("error_msg","Account not found");
            return Redirect::to("/dashboard");
        }
    }

    public function getChangePassword(){
        return View::make('change_password',$this->data);
    }

    public function postChangePassword(){

        $current_password = Input::get('current_password','');
        $password = Input::get('password','');
        $password_confirmation = Input::get('password_confirmation','');

        if($password==$password_confirmation){
            if(Auth::validate(['email'=>Auth::user()->email,'password'=>$current_password])){
                $user = User::find(Auth::user()->id);
                $user->password = Hash::make($password);
                $user->save();

                Session::flash('success_msg', 'Your password was changed successfully.');
                return Redirect::back();

            }else{
                Session::flash('error_msg', 'An invalid password was entered.');
                return Redirect::back();
            }
        }else{
            Session::flash('error_msg', 'Both your new password and your password confirmation must be the same.');
            return Redirect::back();
        }
    }

    public function getRegister()
    {
        return View::make('register',$this->data);
    }

    public function activateUser($user_id=null,$activation_code=null){
        if(is_null($user_id)||is_null($activation_code)){
            Session::flash('error_msg','Unable to activate the account. Please contact support.');
            return Redirect::to('/register');
        }else{
            DB::table('users')->where('id',$user_id)->where('activation_code',$activation_code)->update(['activated'=>1,'activation_code'=>null,'activated_at'=>\Carbon\Carbon::now()]);

            Session::flash('success_msg','Your account has been activated. Please log in below.');
            return Redirect::to('/login');
        }
    }

    public function postRegister()
    {

        $name = Input::get('name');
        $email = Input::get('email');
        $password = Input::get('password');
        $password_confirmation = Input::get('password_confirmation');

        try {

            $user = $this->userManager->createUser(["name" => $name,
                    "email" => $email,
                    "password" => $password,
                    "password_confirmation" => $password_confirmation],
                'customer',
                false);

            $user->avatar = "/assets/images/default-avatar.jpg";
            $user->save();

            $data = [
                'name' => $user->name,
                'user_id' => $user->id,
                'activation_code' => $user->activation_code,
            ];

            $this->mailer->activate($email,$name,$data);

            Session::flash('success_msg', "Registration successful. Please activate your account by clicking the activation link we sent to " . $email);
            return Redirect::back();

        } catch (\KodeInfo\UserManagement\Exceptions\AuthException $e) {
            Session::flash('error_msg', Utils::buildMessages($e->getErrors()));
            return Redirect::back();
        }
    }

    public function getForgotPassword()
    {
        return View::make("forgot-password",$this->data);
    }

    public function postForgotPassword()
    {
        $email = Input::get("email");

        $user = User::where('email', $email)->first();

        if (sizeof($user) <= 0) {
            Session::flash("error_msg", "An account was not found with that e-mail address. Please input another and try again!");
            return Redirect::back();
        } else {
            $reset_code = $this->userManager->generateResetCode();
            $user->reset_password_code = $reset_code;
            $user->reset_requested_on = \Carbon\Carbon::now();
            $user->save();

            $this->mailer->reset_password($user);

            Session::flash('success_msg','Please click on the link we sent to your email to reset password');
            return Redirect::to('/forgot-password');
        }

    }

    public function getReset($email,$code){

        if(strlen($email)<=0 || strlen($code)<=0){
            Session::flash("error_msg","Invalid Request. Please reset your password.");
            return Redirect::to('/forgot-password');
        }

        //Check code and email
        $user = User::where('email',$email)->where('reset_password_code',$code)->first();

        if(sizeof($user)<=0){
            Session::flash("error_msg","Invalid Request. Please reset your password.");
            return Redirect::to('/forgot-password');
        }else{
            //check for 24 hrs for token
            $reset_requested_on = \Carbon\Carbon::createFromFormat('Y-m-d G:i:s',$user->reset_requested_on);
            $present_day = \Carbon\Carbon::now();

            if($reset_requested_on->addDay()>$present_day){
                //Show new password view
                $this->data['email'] = $email;
                $this->data['code'] = $code;
                return View::make('reset-password',$this->data);
            }else{
                Session::flash("error_msg","Your password change token has expired. Please reset your password.");
                return Redirect::to('/forgot-password');
            }
        }
    }

    public function postReset(){

        $password = Input::get('password','');
        $password_confirmation = Input::get('password_confirmation','');

        if($password==$password_confirmation){

            $validate_reset = User::where('email',Input::get('email',''))->where('reset_password_code',Input::get('code',''))->first();

            if(sizeof($validate_reset)>0){
                $user = User::where('email',Input::get('email'))->first();
                $user->password = Hash::make($password);
                $user->save();

                $this->mailer->password_changed($user);

                Session::flash('success_msg', 'Your password was changed successfully.');
                return Redirect::to('/login');
            }else{
                Session::flash('error_msg', 'An invalid password was entered.');
                return Redirect::back();
            }
        }else{
            Session::flash('error_msg', 'Both your new password and your password confirmation must be the same.');
            return Redirect::back();
        }
    }

    public function getLogin()
    {

        if (Auth::check()) {
            return Redirect::route('dashboard');
        }

        return View::make('login',$this->data);
    }

    public function logout()
    {
        if(Auth::check()){
            $user = Auth::user();
            $user->is_online = 0;
            $user->save();
        }

        $this->userManager->logout();
        return Redirect::to('/login');
    }

    public function signInWithFacebook()
    {

        $fb = OAuth::consumer('Facebook');

        if (Input::has('code')) {

            $fb->requestAccessToken(Input::get('code'));

            $result = json_decode($fb->request('/me'), true);

            if (isset($result['email'])) {
                //Is he registered user
                $user = DB::table('users')->where('email', $result['email'])->get();

                if (sizeof($user) > 0) {
                    //is registered so do login
                    try {

                        $this->userManager->loginWithID($result['email'], true);

                        if(Auth::user()->is_admin)
                            return Redirect::route('dashboard');
                        else
                            return Redirect::route('all_sandboxes');

                    } catch (\KodeInfo\UserManagement\Exceptions\LoginFieldsMissingException $e) {
                        Session::flash('error_msg', Utils::buildMessages($e->getErrors()));
                        return Redirect::back();
                    } catch (\KodeInfo\UserManagement\Exceptions\UserNotFoundException $e) {
                        Session::flash('error_msg', Utils::buildMessages($e->getErrors()));
                        return Redirect::back();
                    } catch (\KodeInfo\UserManagement\Exceptions\UserNotActivatedException $e) {
                        Session::flash('error_msg', Utils::buildMessages($e->getErrors()));
                        return Redirect::back();
                    } catch (\KodeInfo\UserManagement\Exceptions\UserBannedException $e) {
                        Session::flash('error_msg', Utils::buildMessages($e->getErrors()));
                        return Redirect::back();
                    } catch (\KodeInfo\UserManagement\Exceptions\UserSuspendedException $e) {
                        Session::flash('error_msg', Utils::buildMessages($e->getErrors()));
                        return Redirect::back();
                    }
                } else {

                    Session::flash('error_msg','An account was not found. Please register below!');
                    return Redirect::to('/register');
                }
            }

            Session::flash('error_msg', 'User was not found. Please register to continue!');
            return Redirect::to('/register');

        } else {
            $url = $fb->getAuthorizationUri();
            return Redirect::away((string)$url);
        }
    }

    public function postLogin()
    {
        try {
            $this->userManager->login(["email" => Input::get('email'),
                "password" => Input::get('password')], Input::has('remember_me'), true);

            return Redirect::route('dashboard');

        } catch (\KodeInfo\UserManagement\Exceptions\AuthException $e) {
            Session::flash('error_msg', Utils::buildMessages($e->getErrors()));
            return Redirect::back();
        }
    }

}