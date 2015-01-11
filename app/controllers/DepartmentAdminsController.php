<?php

use KodeInfo\UserManagement\UserManagement;
use \KodeInfo\Forms\Rules\CustomerAddValidator;
use KodeInfo\Mailers\UsersMailer;

class DepartmentAdminsController extends BaseController
{

    protected $customerAddValidator;
    public $mailer;

    function __construct(CustomerAddValidator $customerAddValidator,UsersMailer $mailer)
    {

        $this->customerAddValidator = $customerAddValidator;

        $this->mailer = $mailer;

        $this->beforeFilter('has_permission:departments_admins.create', array('only' => array('create', 'store')));
        $this->beforeFilter('has_permission:departments_admins.edit', array('only' => array('edit', 'update')));
        $this->beforeFilter('has_permission:departments_admins.all', array('only' => array('all')));
        $this->beforeFilter('has_permission:departments_admins.delete', array('only' => array('delete')));
        $this->beforeFilter('has_permission:departments_admins.remove', array('only' => array('remove')));
        $this->beforeFilter('has_permission:departments_admins.activate', array('only' => array('activate')));

    }

    public function create()
    {
        if (\KodeInfo\Utilities\Utils::isDepartmentAdmin(Auth::user()->id)) {

            $department_admin = DepartmentAdmins::where('user_id', Auth::user()->id)->first();
            $this->data['department'] = Department::where('id', $department_admin->department_id)->first();
            $this->data["company"] = Company::where('id', $this->data['department']->company_id)->first();

        } elseif (\KodeInfo\Utilities\Utils::isOperator(Auth::user()->id)) {

            $department_operator = OperatorsDepartment::where('user_id', Auth::user()->id)->first();
            $this->data['department'] = Department::where('id', $department_operator->department_id)->first();
            $this->data["company"] = Company::where('id', $this->data['department']->company_id)->first();

        } else {

            $companies = Company::all();

            $this->data['departments'] = [];
            $this->data['companies'] = $companies;

            if (sizeof($companies) > 0) {

                $department_ids = Department::where('company_id', $companies[0]->id)->lists('id');

                if (sizeof($department_ids) > 0) {
                    foreach ($department_ids as $department_id) {
                        if (sizeof(DepartmentAdmins::where('department_id', $department_id)->get()) <= 0)
                            array_push($this->data['departments'], Department::whereIn("id", $department_ids)->first());
                    }
                }
            }

        }


        $this->data["countries"] = DB::table("countries")->remember(60)->get();
        $this->data['timezones'] = Config::get("timezones");

        return View::make('department_admins.create', $this->data);
    }

    public function edit($department_admin_id)
    {
        $companies = Company::all();

        $user = User::find($department_admin_id);

        if (sizeof(DepartmentAdmins::where('user_id', $user->id)->get()) > 0)
            $department_id = DepartmentAdmins::where('user_id', $user->id)->pluck("department_id");
        else
            $department_id = 0;

        $this->data['department_id'] = $department_id;
        $this->data['company_id'] = CompanyDepartmentAdmins::where("user_id", $department_admin_id)->pluck("company_id");
        $this->data['departments'] = Department::where("company_id", $this->data['company_id'])->get();
        $this->data['user'] = $user;
        $this->data['user']->company = Company::find($this->data['company_id']);
        $this->data['companies'] = $companies;
        $this->data["countries"] = DB::table("countries")->remember(60)->get();
        $this->data['timezones'] = Config::get("timezones");

        return View::make('department_admins.edit', $this->data);
    }

    public function store()
    {
        $userManager = new UserManagement();

        if (!$this->customerAddValidator->validate(Input::all())) {
            Session::flash('error_msg', Utils::buildMessages($this->customerAddValidator->getValidation()->messages()->all()));
            return Redirect::to('/departments/admins/create')->withInput(Input::except("avatar"));
        } else {
            try {
                $user = $userManager->createUser(["name" => Input::get("name"),
                    "email" => Input::get("email"),
                    "password" => Input::get("password"),
                    "password_confirmation" => Input::get("password_confirmation"),
                    "birthday" => Input::get("birthday"),
                    "bio" => Input::get("bio"),
                    "mobile_no" => Input::get("mobile_no"),
                    "country" => Input::get("country"),
                    "gender" => Input::get("gender"),
                    "timezone" => Input::get("timezone", ""),
                    "show_avatar" => Input::has("show_avatar"),
                    "permissions" => Input::has('permissions') ? implode(",", Input::get('permissions')) : "",
                    "avatar" => Input::hasFile('avatar') ? Utils::imageUpload(Input::file('avatar'), 'profile') : ''],
                    'department-admin',
                    Input::has("activated"));

                $company_department_admin = new CompanyDepartmentAdmins();
                $company_department_admin->user_id = $user->id;
                $company_department_admin->company_id = Input::get('company');
                $company_department_admin->save();

                if (Input::get('department') > 0) {
                    $department_admin = new DepartmentAdmins();
                    $department_admin->user_id = $user->id;
                    $department_admin->department_id = Input::get('department');
                    $department_admin->save();
                }

                $this->mailer->welcome($user->email,$user->name,User::getWelcomeFields(false,$user->id,Input::get("password"),Input::get('company')));

                RecentActivities::createActivity("Department Admin '".$user->id."' created by ID:'".Auth::user()->id."' Name:'".Auth::user()->name."'");

                if(!Input::has("activated"))
                    $this->mailer->activate($user->email,$user->name,User::getActivateFields(false,$user->id,Input::get('company')));


                Session::flash('success_msg', trans('msgs.department_admin_created_success'));
                return Redirect::to('/departments/admins/all');
            } catch (\Exception $e) {
                Session::flash('error_msg', trans('msgs.unable_to_create_department_admin'));
                return Redirect::to('/departments/admins/create')->withInput(Input::except("avatar"));
            }
        }
    }

    public function update()
    {

        if (!Input::has("user_id")) {
            Session::flash("error_msg", trans('msgs.invalid_request'));
            return Redirect::to("/departments/admins/all");
        }

        if(Config::get('site-config.is_demo')&&Input::get("user_id")==2){
            Session::flash('error_msg','Demo : Feature is disabled');
            return Redirect::to('/dashboard');
        }


        try {
            $admin = User::find(Input::get("user_id"));
            $admin->name = Input::get("name");
            $admin->birthday = Input::get("birthday");
            $admin->bio = Input::get("bio");
            $admin->mobile_no = Input::get("mobile_no");
            $admin->country = Input::get("country");
            $admin->gender = Input::get("gender");
            $admin->timezone = Input::get("timezone");
            $admin->show_avatar = Input::has("show_avatar");
            $admin->avatar = Input::hasFile('avatar') ? Utils::imageUpload(Input::file('avatar'), 'profile') : Input::get("old_avatar");
            $admin->save();

            DepartmentAdmins::where('user_id', $admin->id)->delete();
            CompanyDepartmentAdmins::where('user_id', $admin->id)->delete();

            $company_department_admin = new CompanyDepartmentAdmins();
            $company_department_admin->user_id = $admin->id;
            $company_department_admin->company_id = Input::get('company');
            $company_department_admin->save();

            if (Input::get('department') > 0) {
                $department_admin = new DepartmentAdmins();
                $department_admin->user_id = $admin->id;
                $department_admin->department_id = Input::get('department');
                $department_admin->save();
            }

            Session::flash('success_msg', trans('msgs.department_admin_updated_success'));
            return Redirect::to('/departments/admins/all');
        } catch (\Exception $e) {
            Session::flash('error_msg', trans('msgs.unable_to_update_department_admin'));
            return Redirect::to('/departments/admins/update/' . Input::get("user_id"))->withInput(Input::except("avatar"));
        }

    }

    public function delete($admin_id)
    {
        $department_admin = DepartmentAdmins::where("user_id", $admin_id)->first();

        if(Config::get('site-config.is_demo')&&$admin_id==2){
            Session::flash('error_msg','Demo : Feature is disabled');
            return Redirect::to('/dashboard');
        }

        if (!empty($department_admin)) {

            $department = Department::where('id',$department_admin->department_id)->first();

            if(!empty($department)) {

                $company = Company::where('id',$department->company_id)->first();

                //Change all conversations , tickets , threads , thread_messages operator_id
                OnlineUsers::where('operator_id', $admin_id)->update(['operator_id' => $company->user_id]);
                ClosedConversations::where('operator_id', $admin_id)->update(['operator_id' => $company->user_id]);
                MessageThread::where('operator_id', $admin_id)->update(['operator_id' => $company->user_id]);
                Tickets::where('operator_id', $admin_id)->update(['operator_id' => $company->user_id]);
                ThreadMessages::where('sender_id', $admin_id)->update(['sender_id' => $company->user_id]);
            }
        }

        CompanyDepartmentAdmins::where("user_id",$admin_id)->delete();

        DepartmentAdmins::where('user_id', $admin_id)->delete();

        UsersGroups::where('user_id', $admin_id)->delete();

        User::where('id', $admin_id)->delete();

        RecentActivities::createActivity("Department admin deleted by ID:'".Auth::user()->id."' Name:'".Auth::user()->name."'");

        Session::flash('success_msg', trans('msgs.department_admin_deleted_success'));

        return Redirect::to('/departments/admins/all');
    }

    public function remove($admin_id)
    {

        if(Config::get('site-config.is_demo')&&$admin_id==2){
            Session::flash('error_msg','Demo : Feature is disabled');
            return Redirect::to('/dashboard');
        }

        try {
            DepartmentAdmins::where('user_id', $admin_id)->delete();
            Session::flash('success_msg', trans('msgs.department_admin_removed_success'));
            return Redirect::to('/departments/admins/all');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Session::flash("error_msg", trans('msgs.department_admin_not_found'));
            return Redirect::to("/departments/admins/all");
        }

    }

    public function all()
    {

        $user_ids = [];

        if (\KodeInfo\Utilities\Utils::isDepartmentAdmin(Auth::user()->id)) {

            $department_admin = DepartmentAdmins::where('user_id', Auth::user()->id)->first();
            $department = Department::where('id', $department_admin->department_id)->first();

            $department_ids = Department::where('company_id', $department->company_id)->lists('id');

            $user_ids = DepartmentAdmins::whereIn('department_id', $department_ids)->lists('user_id');

        } elseif (\KodeInfo\Utilities\Utils::isOperator(Auth::user()->id)) {

            $department_admin = DepartmentAdmins::where('user_id', Auth::user()->id)->first();
            $department = Department::where('id', $department_admin->department_id)->first();

            $department_ids = Department::where('company_id', $department->company_id)->lists('id');

            $user_ids = DepartmentAdmins::whereIn('department_id', $department_ids)->lists('user_id');

        } else {
            $group = Groups::where("name", "department-admin")->first();
            $user_ids = UsersGroups::where("group_id", $group->id)->lists("user_id");
        }


        if (sizeof($user_ids) > 0) {
            $this->data["admins"] = User::whereIn("id", $user_ids)->orderBy('id','desc')->get();
        } else {
            $this->data["admins"] = [];
        }

        foreach ($this->data["admins"] as $admin) {
            $department_admin = DepartmentAdmins::where('user_id', $admin->id)->first();

            if (!empty($department_admin)) {
                $admin->department = Department::find($department_admin->department_id);
            }

            $company_id = CompanyDepartmentAdmins::where("user_id", $admin->id)->pluck('company_id');
            $admin->company = Company::find($company_id);

        }

        return View::make('department_admins.all', $this->data);
    }

    public function activateAccount($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->activated = 1;
            $user->activated_at = \Carbon\Carbon::now();
            $user->save();
            Session::flash("success_msg", trans('msgs.account_activated_successfully'));
            return Redirect::to("/departments/admins/all");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Session::flash("error_msg", trans('msgs.account_not_found'));
            return Redirect::to("/departments/admins/all");
        }
    }

}