<?php

use KodeInfo\Forms\Rules\OperatorAddValidator;
use KodeInfo\UserManagement\UserManagement;

class OperatorsController extends BaseController {

    protected $operatorAddValidator;

    function __construct(OperatorAddValidator $operatorAddValidator){
        $this->operatorAddValidator = $operatorAddValidator;

        $this->beforeFilter('has_permission:operators.create', array('only' => array('create','store')));
        $this->beforeFilter('has_permission:operators.edit', array('only' => array('edit','update')));
        $this->beforeFilter('has_permission:operators.view', array('only' => array('all')));
        $this->beforeFilter('has_permission:operators.delete', array('only' => array('delete')));
        $this->beforeFilter('has_permission:operators.activate', array('only' => array('activate')));

    }

    public function create(){

        if(\KodeInfo\Utilities\Utils::isDepartmentAdmin(Auth::user()->id)){
            $department_admin = DepartmentAdmins::where('user_id',Auth::user()->id)->first();
            $this->data['department'] = Department::where('id',$department_admin->department_id)->first();
            $this->data["company"] = Company::where('id',$this->data['department']->company_id)->first();

            $permissions_keys = explode(",",$this->data['department']);
            $permissions = Permissions::whereIn('key',$permissions_keys)->get();
            $this->data['permissions'] = $permissions;

        }else{

            $companies = Company::all();

            $this->data['companies'] = $companies;

            if(sizeof($companies)>0){
                $department = Department::where("company_id",$companies[0]->id)->first();

                if(empty($department)){
                    Session::flash('error_msg','Please create department before adding operators');
                    return Redirect::back();
                }

                $permissions_keys = explode(",",$department->permissions);
                $permissions = Permissions::whereIn('key',$permissions_keys)->get();
                $this->data['permissions'] = $permissions;
                $this->data['departments'] = Department::where("company_id",$companies[0]->id)->get();
            }else{
                $this->data['permissions'] = [];
                $this->data['departments'] = [];
            }
        }

        $this->data["countries"] = DB::table("countries")->remember(60)->get();
        $this->data['timezones'] = Config::get("timezones");

        return View::make('operators.create',$this->data);
    }

    public function edit($id){

        $companies = Company::all();

        $this->data['operator'] = User::find($id);
        $department_id = OperatorsDepartment::where('user_id',$this->data["operator"]->id)->pluck("department_id");
        $department = Department::find($department_id);

        $this->data['company_id'] = $department->company_id;
        $this->data['department_id'] = $department->id;

        $permissions_keys = explode(",",$department->permissions);
        $permissions = Permissions::whereIn('key',$permissions_keys)->get();
        $this->data['permissions'] = $permissions;
        $this->data['operator_permissions'] = explode(",",$this->data['operator']->permissions);

        $this->data['departments'] = Department::where("company_id",$department->company_id)->get();

        $this->data['companies'] = $companies;
        $this->data["countries"] = DB::table("countries")->remember(60)->get();
        $this->data['timezones'] = Config::get("timezones");

        return View::make('operators.edit',$this->data);
    }

    public function store(){

        $userManager = new UserManagement();

        if(!$this->operatorAddValidator->validate(Input::all())){
            Session::flash('error_msg',Utils::buildMessages($this->operatorAddValidator->getValidation()->messages()->all()));
            return Redirect::to('/operators/create')->withInput(Input::except("avatar"));
        }else{
            try
            {
                $user = $userManager->createUser(["name" => Input::get("name"),
                        "email" => Input::get("email"),
                        "password" => Input::get("password"),
                        "password_confirmation" => Input::get("password_confirmation"),
                        "birthday" => Input::get("birthday"),
                        "bio" => Input::get("bio"),
                        "mobile_no" => Input::get("mobile_no"),
                        "country" => Input::get("country"),
                        "gender" => Input::get("gender"),
                        "timezone" => Input::get("timezone"),
                        "show_avatar" => Input::has("show_avatar"),
                        "permissions" => Input::has('permissions') ? implode(",",Input::get('permissions')) : "",
                        "avatar" => Input::hasFile('avatar')?Utils::imageUpload(Input::file('avatar'),'profile'):''],
                    'operator',
                    Input::has("activated"));

                $operator_department = new OperatorsDepartment();
                $operator_department->user_id = $user->id;
                $operator_department->department_id = Input::get('department');
                $operator_department->save();

                Session::flash('success_msg',"Operator created successfully");
                return Redirect::to('/operators/all');
            }
            catch (\Exception $e){
                Session::flash('error_msg',"Unable to create operator");
                return Redirect::to('/operators/create')->withInput(Input::except("avatar"));
            }
        }
    }

    public function update(){

        if(!Input::has("id")){
            Session::flash("error_msg","Invalid request");
            return Redirect::to("/operators/all");
        }

        $this->operatorAddValidator->addRule("email","");
        $this->operatorAddValidator->addRule("password","");
        $this->operatorAddValidator->addRule("password_confirmation","");

        if(!$this->operatorAddValidator->validate(Input::all())){
            Session::flash('error_msg',Utils::buildMessages($this->operatorAddValidator->getValidation()->messages()->all()));
            return Redirect::to('/operators/update/'.Input::get("id"))->withInput(Input::except("avatar"));
        }else{
            try
            {
                $operator = User::find(Input::get("id"));
                $operator->name=Input::get("name");
                $operator->birthday=Input::get("birthday");
                $operator->bio=Input::get("bio");
                $operator->mobile_no=Input::get("mobile_no");
                $operator->country=Input::get("country");
                $operator->gender=Input::get("gender");
                $operator->timezone=Input::get("timezone");
                $operator->show_avatar=Input::has("show_avatar");
                $operator->avatar=Input::hasFile('avatar')?Utils::imageUpload(Input::file('avatar'),'profile'):Input::get("old_avatar");
                $operator->permissions =Input::has('permissions') ? implode(",",Input::get('permissions')) : "";
                $operator->save();

                OperatorsDepartment::where("user_id",$operator->id)->delete();

                $operator_department = new OperatorsDepartment();
                $operator_department->user_id = $operator->id;
                $operator_department->department_id = Input::get('department');
                $operator_department->save();

                Session::flash('success_msg',"Operator updated successfully");
                return Redirect::to('/operators/all');
            }
            catch (\Exception $e){
                Session::flash('error_msg',"Unable to update operator");
                return Redirect::to('/operators/update/'.Input::get("id"))->withInput(Input::except("avatar"));
            }
        }
    }

    public function delete($user_id){
        User::find($user_id)->delete();
        OperatorsDepartment::where("user_id",$user_id)->delete();
        Session::flash('success_msg',"Operator deleted successfully");
        return Redirect::to('/operators/all');
    }

    public function activate($id){
        try {
            $user = User::findOrFail($id);
            $user->activated = 1;
            $user->activated_at = \Carbon\Carbon::now();
            $user->save();
            Session::flash("success_msg","Operator activated successfully");
            return Redirect::to("/operators/all");
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            Session::flash("error_msg","Operator not found");
            return Redirect::to("/operators/all");
        }
    }

    public function all(){

        $group = Groups::where("name","operator")->first();

        $user_ids = UsersGroups::where("group_id",$group->id)->lists("user_id");

        if(sizeof($user_ids)>0){
            $this->data["operators"] = User::whereIn("id",$user_ids)->get();
        }else{
            $this->data["operators"] = [];
        }

        foreach($this->data["operators"] as $operator){
            $department_id = OperatorsDepartment::where('user_id',$operator->id)->pluck("department_id");
            $department = Department::find($department_id);
            $company = Company::find($department->company_id);

            $operator->department = $department;
            $operator->company = $company;
        }

        $this->data['permissions'] = Permissions::all();
        $this->data['departments'] = Department::all();
        return View::make('operators.all',$this->data);
    }
}