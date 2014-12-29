<?php

use KodeInfo\Forms\Rules\AccountAddValidator;
use KodeInfo\UserManagement\UserManagement;
use KodeInfo\Mailers\UsersMailer;

class CustomersController extends BaseController
{

    protected $accountAddValidator;
    public $mailer;

    function __construct(AccountAddValidator $accountAddValidator, UsersMailer $mailer)
    {
        $this->accountAddValidator = $accountAddValidator;
        $this->mailer = $mailer;

        $this->beforeFilter('has_permission:customers.create', array('only' => array('create', 'store')));
        $this->beforeFilter('has_permission:customers.edit', array('only' => array('edit', 'update')));
        $this->beforeFilter('has_permission:customers.all', array('only' => array('all')));
        $this->beforeFilter('has_permission:customers.delete', array('only' => array('delete')));
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

            $this->data['companies'] = Company::all();

        }

        $this->data['timezones'] = Config::get("timezones");
        $this->data['countries'] = DB::table('countries')->get();

        return View::make('customers.create', $this->data);
    }

    public function edit($id)
    {

        try {
            $this->data["user"] = User::findOrFail($id);

            $company_id = CompanyCustomers::where("customer_id", $this->data["user"]->id)->pluck('company_id');
            $this->data["user"]->company = Company::find($company_id);

            $this->data["countries"] = DB::table("countries")->remember(60)->get();
            $this->data["companies"] = Company::all();
            $this->data['timezones'] = Config::get("timezones");

            return View::make('customers.edit', $this->data);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Session::flash("error_msg", trans('msgs.customer_not_found'));
            return Redirect::to("/customers/all");
        }

    }

    public function store()
    {

        $userManager = new UserManagement();

        $this->accountAddValidator->addRule("company", "required");

        if (!$this->accountAddValidator->validate(Input::all())) {
            Session::flash('error_msg', Utils::buildMessages($this->accountAddValidator->getValidation()->messages()->all()));
            return Redirect::to('/customers/create')->withInput(Input::except("avatar"));
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
                    "avatar" => Input::hasFile('avatar') ? Utils::imageUpload(Input::file('avatar'), 'profile') : ''],
                    'customer',
                    Input::has("activated"));

                $company_users = new CompanyCustomers();
                $company_users->customer_id = $user->id;
                $company_users->company_id = Input::get("company");
                $company_users->save();

                $this->mailer->welcome($user->email, $user->name, User::getWelcomeFields(false, $user->id, Input::get("password"), Input::get('company')));

                if (!Input::has("activated"))
                    $this->mailer->activate($user->email, $user->name, User::getActivateFields(false, $user->id, Input::get('company')));


                Session::flash('success_msg', trans('msgs.customer_created_success'));
                return Redirect::to('/customers/all');
            } catch (\Exception $e) {
                Session::flash('error_msg', trans('msgs.unable_to_add_customer'));
                return Redirect::to('/customers/create')->withInput(Input::except("avatar"));
            }
        }

    }

    public function update()
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

            CompanyCustomers::where("customer_id", $user->id)->delete();

            $company_users = new CompanyCustomers();
            $company_users->customer_id = $user->id;
            $company_users->company_id = Input::get("company");
            $company_users->save();

            Session::flash("success_msg", trans('msgs.customer_updated_success'));
            return Redirect::to("/customers/all");

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Session::flash("error_msg", trans('msgs.customer_not_found'));
            return Redirect::to("/customers/all");
        }
    }

    public function delete($user_id)
    {
        $tickets = Tickets::where('customer_id', $user_id)->get();

        //Delete tickets
        foreach ($tickets as $ticket) {
            TicketAttachments::where('thread_id', $ticket->id)->delete();
            MessageThread::where('id', $ticket->thread_id)->delete();
            ThreadMessages::where('thread_id', $ticket->thread_id)->delete();
        }

        Tickets::where('customer_id', $user_id)->delete();

        //Delete Chat and Conversations
        $online_users = OnlineUsers::where('user_id', $user_id)->get();

        foreach ($online_users as $online_user) {
            MessageThread::where('id', $online_user->thread_id)->delete();
            ThreadMessages::where('thread_id', $online_user->thread_id)->delete();
        }

        OnlineUsers::where('user_id', $user_id)->delete();

        $closed_conversations = ClosedConversations::where('user_id', $user_id)->get();

        foreach ($closed_conversations as $closed_conversation) {
            MessageThread::where('id', $closed_conversation->thread_id)->delete();
            ThreadMessages::where('thread_id', $closed_conversation->thread_id)->delete();
        }

        ClosedConversations::where('user_id', $user_id)->delete();

        UsersGroups::where('user_id', $user_id)->delete();

        CompanyCustomers::where("customer_id", $user_id)->delete();

        User::where("id", $user_id)->delete();

        Session::flash('success_msg', trans('msgs.customer_deleted_success'));

        return Redirect::to('/customers/all');
    }

    public function all()
    {

        $customer_ids = [];

        if (\KodeInfo\Utilities\Utils::isDepartmentAdmin(Auth::user()->id)) {

            $department_admin = DepartmentAdmins::where('user_id', Auth::user()->id)->first();
            $department = Department::where('id', $department_admin->department_id)->first();

            $customer_ids = CompanyCustomers::where("company_id", $department->company_id)->lists('customer_id');

        } elseif (\KodeInfo\Utilities\Utils::isOperator(Auth::user()->id)) {

            $department_admin = OperatorsDepartment::where('user_id', Auth::user()->id)->first();
            $department = Department::where('id', $department_admin->department_id)->first();

            $customer_ids = CompanyCustomers::where("company_id", $department->company_id)->lists('customer_id');

        } else {

            $customer_ids = CompanyCustomers::lists('customer_id');

        }


        if (sizeof($customer_ids) > 0) {
            $this->data["customers"] = User::whereIn("id", $customer_ids)->get();
        } else {
            $this->data["customers"] = [];
        }

        foreach ($this->data["customers"] as $customer) {
            $company_id = CompanyCustomers::where("customer_id", $customer->id)->pluck('company_id');
            $customer->company = Company::find($company_id);

            $customer->all_ticket_count = Tickets::where('customer_id', $customer->id)->count();
            $customer->pending_ticket_count = Tickets::where('customer_id', $customer->id)->where('status', Tickets::TICKET_PENDING)->count();
            $customer->resolved_ticket_count = Tickets::where('customer_id', $customer->id)->where('status', Tickets::TICKET_RESOLVED)->count();
        }

        return View::make('customers.all', $this->data);
    }

}