<?php

use KodeInfo\Utilities\Utils;
use KodeInfo\UserManagement\UserManagement;
use KodeInfo\Mailers\UsersMailer;

class AuthController extends BaseController
{

    public $userManager;
    public $mailer;

    function __construct(UserManagement $userManager, UsersMailer $mailer)
    {
        $this->userManager = $userManager;
        $this->mailer = $mailer;
    }

    public function profile()
    {
        try {
            $this->data["user"] = User::findOrFail(Auth::user()->id);

            $this->data["countries"] = DB::table("countries")->remember(60)->get();
            $this->data['timezones'] = Config::get("timezones");

            return View::make('profile', $this->data);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Session::flash("error_msg", trans('msgs.account_not_found'));
            return Redirect::to("/dashboard");
        }
    }

    public function storeProfile()
    {

        try {
            $user = User::findOrFail(Input::get("user_id"));
            $user->name = Input::get("name");
            $user->birthday = Input::get("birthday");
            $user->bio = Input::get("bio");
            $user->mobile_no = Input::get("mobile_no");
            $user->country = Input::get("country");
            $user->gender = Input::get("gender");
            $user->avatar = Input::hasFile('avatar') ? Utils::imageUpload(Input::file('avatar'), 'profile') : Input::get("old_avatar");
            $user->save();


            Session::flash("success_msg", trans('msgs.profile_updated_success'));
            return Redirect::to("/profile");

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Session::flash("error_msg", trans('msgs.account_not_found'));
            return Redirect::to("/dashboard");
        }
    }

    public function getChangePassword()
    {
        return View::make('change-password', $this->data);
    }

    public function postChangePassword()
    {

        if(Config::get('site-config.is_demo')){
            Session::flash('error_msg','Demo : Feature is disabled');
            return Redirect::to('/dashboard');
        }

        $current_password = Input::get('current_password', '');
        $password = Input::get('password', '');
        $password_confirmation = Input::get('password_confirmation', '');

        if ($password == $password_confirmation) {
            if (Auth::validate(['email' => Auth::user()->email, 'password' => $current_password])) {
                $user = User::find(Auth::user()->id);
                $user->password = Hash::make($password);
                $user->save();

                Session::flash('success_msg', trans('msgs.your_password_changed_success'));
                return Redirect::back();

            } else {
                Session::flash('error_msg', trans('msgs.invalid_password_entered'));
                return Redirect::back();
            }
        } else {
            Session::flash('error_msg', trans('msgs.both_passwords_should_be_same'));
            return Redirect::back();
        }
    }

    public function activateUser($user_id = null, $activation_code = null)
    {
        if (is_null($user_id) || is_null($activation_code)) {
            Session::flash('error_msg', trans('msgs.unable_to_activate_account_contact_support'));
            return Redirect::to('/login');
        } else {
            DB::table('users')->where('id', $user_id)->where('activation_code', $activation_code)->update(['activated' => 1, 'activation_code' => null, 'activated_at' => \Carbon\Carbon::now()]);

            Session::flash('success_msg', trans('msgs.account_activated_login_below'));
            return Redirect::to('/login');
        }
    }

    public function getForgotPassword()
    {
        return View::make("forgot-password", $this->data);
    }

    public function postForgotPassword()
    {
        if(Config::get('site-config.is_demo')){
            Session::flash('error_msg','Demo : Feature is disabled');
            return Redirect::to('/dashboard');
        }

        $email = Input::get("email");

        $user = User::where('email', $email)->first();

        if (sizeof($user) <= 0) {
            Session::flash("error_msg", trans('msgs.account_not_found_with_email_try_again'));
            return Redirect::back();
        } else {
            $reset_code = $this->userManager->generateResetCode();
            $user->reset_password_code = $reset_code;
            $user->reset_requested_on = \Carbon\Carbon::now();
            $user->save();

            $this->mailer->reset_password($user->email, $user->name, User::getResetPasswordFields(false, $user->id));

            Session::flash('success_msg', trans('msgs.please_click_on_link_to_reset'));
            return Redirect::to('/forgot-password');
        }

    }

    public function getReset($email, $code)
    {

        if (strlen($email) <= 0 || strlen($code) <= 0) {
            Session::flash("error_msg", trans('msgs.invalid_request_reset_password'));
            return Redirect::to('/forgot-password');
        }

        //Check code and email
        $user = User::where('email', $email)->where('reset_password_code', $code)->first();

        if (sizeof($user) <= 0) {
            Session::flash("error_msg", trans('msgs.invalid_request_reset_password'));
            return Redirect::to('/forgot-password');
        } else {
            //check for 24 hrs for token
            $reset_requested_on = \Carbon\Carbon::createFromFormat('Y-m-d G:i:s', $user->reset_requested_on);
            $present_day = \Carbon\Carbon::now();

            if ($reset_requested_on->addDay() > $present_day) {
                //Show new password view
                $this->data['email'] = $email;
                $this->data['code'] = $code;
                return View::make('reset-password', $this->data);
            } else {
                Session::flash("error_msg", trans('msgs.reset_password_token_expired'));
                return Redirect::to('/forgot-password');
            }
        }
    }

    public function postReset()
    {

        $password = Input::get('password', '');
        $password_confirmation = Input::get('password_confirmation', '');

        if ($password == $password_confirmation) {

            $validate_reset = User::where('email', Input::get('email', ''))->where('reset_password_code', Input::get('code', ''))->first();

            if (sizeof($validate_reset) > 0) {
                $user = User::where('email', Input::get('email'))->first();
                $user->password = Hash::make($password);
                $user->save();

                $this->mailer->password_changed($user->email, $user->name, User::getPasswordChangedFields(false, $user->id));

                Session::flash('success_msg', trans('msgs.password_changed_success'));
                return Redirect::to('/login');
            } else {
                Session::flash('error_msg', trans('msgs.invalid_password_entered'));
                return Redirect::back();
            }
        } else {
            Session::flash('error_msg', trans('msgs.both_passwords_should_be_same'));
            return Redirect::back();
        }
    }

    public function getLogin()
    {

        if (Auth::check()) {
            return Redirect::route('dashboard');
        }

        return View::make('login', $this->data);
    }

    public function logout()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->is_online = 0;
            $user->save();
        }

        $this->userManager->logout();
        return Redirect::to('/login');
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