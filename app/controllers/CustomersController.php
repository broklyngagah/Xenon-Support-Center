<?php

use KodeInfo\Forms\Rules\AccountAddValidator;
use KodeInfo\UserManagement\UserManagement;

class CustomersController extends BaseController {

    protected $accountAddValidator;

    function __construct(AccountAddValidator $accountAddValidator){
        $this->accountAddValidator = $accountAddValidator;

        $this->beforeFilter('has_permission:customers.create', array('only' => array('create','store')));
        $this->beforeFilter('has_permission:customers.edit', array('only' => array('edit','update')));
        $this->beforeFilter('has_permission:customers.all', array('only' => array('all')));
        $this->beforeFilter('has_permission:customers.delete', array('only' => array('delete')));
    }

    public function create(){


        if(\KodeInfo\Utilities\Utils::isDepartmentAdmin(Auth::user()->id)){

            $department_admin = DepartmentAdmins::where('user_id',Auth::user()->id)->first();
            $this->data['department'] = Department::where('id',$department_admin->department_id)->first();
            $this->data["company"] = Company::where('id',$this->data['department']->company_id)->first();

        }elseif (\KodeInfo\Utilities\Utils::isOperator(Auth::user()->id)) {

            $department_operator = OperatorsDepartment::where('user_id',Auth::user()->id)->first();
            $this->data['department'] = Department::where('id',$department_operator->department_id)->first();
            $this->data["company"] = Company::where('id',$this->data['department']->company_id)->first();

        }else {

            $this->data['companies'] = Company::all();

        }

        $this->data['timezones'] = Config::get("timezones");
        $this->data['countries'] = DB::table('countries')->get();

        return View::make('customers.create',$this->data);
    }

    public function edit($id){

        try {
            $this->data["user"] = User::findOrFail($id);

            $company_id = CompanyCustomers::where("customer_id",$this->data["user"]->id)->pluck('company_id');
            $this->data["user"]->company = Company::find($company_id);

            $this->data["countries"] = DB::table("countries")->remember(60)->get();
            $this->data["companies"] = Company::all();
            $this->data['timezones'] = Config::get("timezones");

            return View::make('customers.edit',$this->data);
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Session::flash("error_msg", "Customer not found");
            return Redirect::to("/customers/all");
        }

    }

    public function store(){

        $userManager = new UserManagement();

        $this->accountAddValidator->addRule("company","required");

        if(!$this->accountAddValidator->validate(Input::all())){
            Session::flash('error_msg',Utils::buildMessages($this->accountAddValidator->getValidation()->messages()->all()));
            return Redirect::to('/customers/create')->withInput(Input::except("avatar"));
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
                        "avatar" => Input::hasFile('avatar')?Utils::imageUpload(Input::file('avatar'),'profile'):''],
                    'customer',
                    Input::has("activated"));

                $company_users = new CompanyCustomers();
                $company_users->customer_id = $user->id;
                $company_users->company_id = Input::get("company");
                $company_users->save();

                Session::flash('success_msg',"Customer created successfully");
                return Redirect::to('/customers/all');
            }
            catch (\Exception $e){
                Session::flash('error_msg',"Unable to add customer");
                return Redirect::to('/customers/create')->withInput(Input::except("avatar"));
            }
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

            CompanyCustomers::where("customer_id",$user->id)->delete();

            $company_users = new CompanyCustomers();
            $company_users->customer_id = $user->id;
            $company_users->company_id = Input::get("company");
            $company_users->save();

            Session::flash("success_msg","Customer updated successfully");
            return Redirect::to("/customers/all");

        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            Session::flash("error_msg","Customer not found");
            return Redirect::to("/customers/all");
        }
    }

    public function delete($user_id){
        User::where("id",$user_id)->delete();
        CompanyCustomers::where("customer_id",$user_id)->delete();
        Session::flash('success_msg',"Customer deleted successfully");
        return Redirect::to('/customers/all');
    }

    public function all()
    {

        $customer_ids = CompanyCustomers::lists('customer_id');

        if(sizeof($customer_ids)>0){
            $this->data["customers"] = User::whereIn("id",$customer_ids)->get();
        }else{
            $this->data["customers"] = [];
        }

        foreach($this->data["customers"] as $customer){
            $company_id = CompanyCustomers::where("customer_id",$customer->id)->pluck('company_id');
            $customer->company = Company::find($company_id);

            $customer->all_ticket_count = Tickets::where('customer_id',$customer->id)->count();
            $customer->pending_ticket_count = Tickets::where('customer_id',$customer->id)->where('status',Tickets::TICKET_PENDING)->count();
            $customer->resolved_ticket_count = Tickets::where('customer_id',$customer->id)->where('status',Tickets::TICKET_RESOLVED)->count();
        }

        return View::make('customers.all', $this->data);
    }

}