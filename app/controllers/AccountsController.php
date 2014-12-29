<?php

use KodeInfo\Forms\Rules\AccountAddValidator;
use KodeInfo\UserManagement\UserManagement;

class AccountsController extends BaseController {

    protected $accountAddValidator;

    function __construct(AccountAddValidator $accountAddValidator){
        $this->accountAddValidator = $accountAddValidator;
    }

    public function create(){
        $this->data["countries"] = DB::table("countries")->remember(60)->get();
        return View::make('accounts.create',$this->data);
    }

    public function edit($id){

        try {
            $this->data["user"] = User::findOrFail($id);
            $this->data["countries"] = DB::table("countries")->remember(60)->get();
            return View::make('accounts.edit',$this->data);
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            Session::flash("error_msg",trans('users.account_not_found'));
            return Redirect::to("/accounts/all");
        }

    }

    public function store(){

        $userManager = new UserManagement();

        if(!$this->accountAddValidator->validate(Input::all())){
            Session::flash('error_msg',Utils::buildMessages($this->accountAddValidator->getValidation()->messages()->all()));
            return Redirect::to('/accounts/create')->withInput(Input::except("avatar"));
        }else{
            try
            {

                $userManager->createUser(["name" => Input::get("name"),
                            "email" => Input::get("email"),
                            "password" => Input::get("password"),
                            "password_confirmation" => Input::get("password_confirmation"),
                            "birthday" => Input::get("birthday"),
                            "bio" => Input::get("bio"),
                            "mobile_no" => Input::get("mobile_no"),
                            "country" => Input::get("country"),
                            "gender" => Input::get("gender"),
                            "avatar" => Input::hasFile('avatar')?Utils::imageUpload(Input::file('avatar'),'profile'):''],
                            'admin',
                            Input::has("activated"));

                Session::flash('success_msg',trans('users.account_created_success'));
                return Redirect::to('/accounts/all');
            }
            catch (\Exception $e){
                Session::flash('error_msg',trans('users.account_not_found'));
                return Redirect::to('/accounts/create')->withInput(Input::except("avatar"));
            }
        }
    }

    public function activateAccount($id){
        try {
            $user = User::findOrFail($id);
            $user->activated = 1;
            $user->activated_at = \Carbon\Carbon::now();
            $user->save();
            Session::flash("success_msg",trans('users.account_activated_success'));
            return Redirect::to("/accounts/all");
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            Session::flash("error_msg",trans('users.account_not_found'));
            return Redirect::to("/accounts/all");
        }
    }

    public function update(){

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

            Session::flash("success_msg",trans("users.account_updated_success"));
            return Redirect::to("/accounts/all");

        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            Session::flash("error_msg",trans('users.account_not_found'));
            return Redirect::to("/accounts/all");
        }

    }

    public function delete($user_id){
        try {
            User::findOrFail($user_id)->delete();
            Session::flash('success_msg', trans('users.account_deleted_success'));
            return Redirect::to('/accounts/all');
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            Session::flash("error_msg",trans('users.account_not_found'));
            return Redirect::to("/accounts/all");
        }
    }

    public function all(){

        $group = Groups::where("name","admin")->first();

        $user_ids = UsersGroups::where("group_id",$group->id)->lists("user_id");

        if(sizeof($user_ids)>0){
            $this->data["users"] = User::whereIn("id",$user_ids)->get();
        }else{
            $this->data["users"] = [];
        }

        return View::make('accounts.all',$this->data);
    }
}