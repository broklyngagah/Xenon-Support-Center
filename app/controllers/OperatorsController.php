<?php

use KodeInfo\Forms\Rules\OperatorAddValidator;
use KodeInfo\UserManagement\UserManagement;
use KodeInfo\Mailers\UsersMailer;

class OperatorsController extends BaseController {

    protected $operatorAddValidator;
    public $mailer;

    function __construct(OperatorAddValidator $operatorAddValidator,UsersMailer $mailer){
        $this->operatorAddValidator = $operatorAddValidator;
        $this->mailer = $mailer;

        $this->beforeFilter('has_permission:operators.create', array('only' => array('create','store')));
        $this->beforeFilter('has_permission:operators.edit', array('only' => array('edit','update')));
        $this->beforeFilter('has_permission:operators.all', array('only' => array('all')));
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

        }elseif (\KodeInfo\Utilities\Utils::isOperator(Auth::user()->id)) {

            $department_operator = OperatorsDepartment::where('user_id',Auth::user()->id)->first();
            $this->data['department'] = Department::where('id',$department_operator->department_id)->first();
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
                    Session::flash('error_msg',trans('msgs.create_department_before_adding_operators'));
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

                $this->mailer->welcome($user->email,$user->name,User::getWelcomeFields(false,$user->id,Input::get("password"),Input::get('company')));

                if(!Input::has("activated"))
                    $this->mailer->activate($user->email,$user->name,User::getActivateFields(false,$user->id,Input::get('company')));

                Session::flash('success_msg',trans('msgs.operator_created_success'));
                return Redirect::to('/operators/all');
            }
            catch (\Exception $e){
                Session::flash('error_msg',trans('msgs.unable_to_create_operator'));
                return Redirect::to('/operators/create')->withInput(Input::except("avatar"));
            }
        }
    }

    public function update(){

        if(!Input::has("id")){
            Session::flash("error_msg",trans('msgs.invalid_request'));
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

                Session::flash('success_msg',trans('msgs.operator_updated_success'));
                return Redirect::to('/operators/all');
            }
            catch (\Exception $e){
                Session::flash('error_msg',trans('msgs.unable_to_update_operator'));
                return Redirect::to('/operators/update/'.Input::get("id"))->withInput(Input::except("avatar"));
            }
        }
    }

    public function delete($user_id){

        $operators_department = OperatorsDepartment::where("user_id",$user_id)->first();

        if(!empty($operators_department)){
            $department_admin = DepartmentAdmins::where('department_id',$operators_department->department_id)->first();

            if(!empty($department_admin)){
                //Change all conversations , tickets , threads , thread_messages operator_id
                OnlineUsers::where('operator_id',$user_id)->update(['operator_id'=>$department_admin->user_id]);
                ClosedConversations::where('operator_id',$user_id)->update(['operator_id'=>$department_admin->user_id]);
                MessageThread::where('operator_id',$user_id)->update(['operator_id'=>$department_admin->user_id]);
                Tickets::where('operator_id',$user_id)->update(['operator_id'=>$department_admin->user_id]);
                ThreadMessages::where('sender_id',$user_id)->update(['sender_id'=>$department_admin->user_id]);
            }

        }

        User::find($user_id)->delete();
        OperatorsDepartment::where("user_id",$user_id)->delete();
        CannedMessages::where('operator_id',$user_id)->delete();
        UsersGroups::where('user_id',$user_id)->delete();

        Session::flash('success_msg',trans('msgs.operator_deleted_success'));
        return Redirect::to('/operators/all');
    }

    public function activate($id){
        try {
            $user = User::findOrFail($id);
            $user->activated = 1;
            $user->activated_at = \Carbon\Carbon::now();
            $user->save();
            Session::flash("success_msg",trans('msgs.operator_activated_success'));
            return Redirect::to("/operators/all");
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            Session::flash("error_msg",trans('msgs.operator_not_found'));
            return Redirect::to("/operators/all");
        }
    }

    public function all(){

        if (\KodeInfo\Utilities\Utils::isDepartmentAdmin(Auth::user()->id)) {

            $department_admin = DepartmentAdmins::where('user_id', Auth::user()->id)->first();
            $department = Department::where('id', $department_admin->department_id)->first();

            $user_ids = OperatorsDepartment::where('department_id',$department->id)->lists('user_id');

        } else {
            $group = Groups::where("name","operator")->first();
            $user_ids = UsersGroups::where("group_id",$group->id)->lists("user_id");
        }

        if(sizeof($user_ids)>0){
            $this->data["operators"] = User::whereIn("id",$user_ids)->orderBy('id','desc')->get();
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

    public function online(){

        if (\KodeInfo\Utilities\Utils::isDepartmentAdmin(Auth::user()->id)) {

            $department_admin = DepartmentAdmins::where('user_id', Auth::user()->id)->first();
            $department = Department::where('id', $department_admin->department_id)->first();

            $user_ids = OperatorsDepartment::where('department_id',$department->id)->lists('user_id');

        } else {
            $group = Groups::where("name","operator")->first();
            $user_ids = UsersGroups::where("group_id",$group->id)->lists("user_id");
        }

        if(sizeof($user_ids)>0){
            $this->data["operators"] = User::whereIn("id",$user_ids)->where("is_online",1)->get();
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