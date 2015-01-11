<?php

use KodeInfo\Forms\Rules\CompanyAddValidator;

class CompaniesController extends BaseController
{

    protected $companyAddValidator;

    function __construct(CompanyAddValidator $companyAddValidator)
    {
        $this->companyAddValidator = $companyAddValidator;

        $this->beforeFilter('has_permission:companies.create', array('only' => array('create', 'store')));
        $this->beforeFilter('has_permission:companies.edit', array('only' => array('edit', 'update')));
        $this->beforeFilter('has_permission:companies.delete', array('only' => array('delete')));
        $this->beforeFilter('has_permission:companies.all', array('only' => array('all')));
    }

    public function getOperators($company_id)
    {
        $department_ids = Department::where('company_id', $company_id)->lists('id');

        $operators = [];

        if (sizeof($department_ids) > 0) {

            $operator_ids = OperatorsDepartment::whereIn('department_id', $department_ids)->lists('user_id');

            if (sizeof($operator_ids) > 0)
                $operators = User::whereIn('id', $operator_ids)->get();
        }


        foreach ($operators as $operator) {
            $department_id = OperatorsDepartment::where('user_id', $operator->id)->pluck("department_id");
            $department = Department::find($department_id);
            $company = Company::find($department->company_id);

            $operator->department = $department;
            $operator->company = $company;
        }

        $this->data['operators'] = $operators;

        return View::make('companies.operators', $this->data);
    }

    public function getCustomers($company_id)
    {
        $customer_ids = CompanyCustomers::where('company_id', $company_id)->lists('customer_id');

        $customers = [];

        if (sizeof($customer_ids) > 0) {
            $customers = User::whereIn('id', $customer_ids)->get();
        }

        foreach ($customers as $customer) {
            $company_id = CompanyCustomers::where("customer_id", $customer->id)->pluck('company_id');
            $customer->company = Company::find($company_id);

            $customer->all_ticket_count = Tickets::where('customer_id', $customer->id)->count();
            $customer->pending_ticket_count = Tickets::where('customer_id', $customer->id)->where('status', Tickets::TICKET_PENDING)->count();
            $customer->resolved_ticket_count = Tickets::where('customer_id', $customer->id)->where('status', Tickets::TICKET_RESOLVED)->count();

        }

        $this->data['customers'] = $customers;

        return View::make('companies.customers', $this->data);
    }

    public function create()
    {
        $group = Groups::where("name", "admin")->first();
        $user_ids = UsersGroups::where("group_id", $group->id)->lists("user_id");

        if (sizeof($user_ids) > 0) {
            $this->data["users"] = User::whereIn("id", $user_ids)->get();
        } else {
            $this->data["users"] = [];
        }

        return View::make('companies.create', $this->data);
    }

    public function store()
    {

        if (!$this->companyAddValidator->validate(Input::all())) {
            Session::flash('error_msg', Utils::buildMessages($this->companyAddValidator->getValidation()->messages()->all()));
            return Redirect::to('/companies/create')->withInput();
        } else {
            try {
                $company = new Company();
                $company->name = Input::get('name');
                $company->description = Input::get('description', '');
                $company->domain = Input::get('domain');
                $company->user_id = Auth::user()->id;
                $company->logo = Input::hasFile('logo') ? Utils::imageUpload(Input::file('logo'), 'companies') : '';
                $company->save();

                RecentActivities::createActivity("Company '".$company->name."' created #".$company->id." by User '".Auth::user()->name."' ID '".Auth::user()->id."'");

                Session::flash('success_msg', trans('msgs.company_created_success'));
                return Redirect::to('/companies/create')->withInput();

            } catch (\Exception $e) {
                Session::flash('error_msg', trans('msgs.unable_to_add_company'));
                return Redirect::to('/companies/create')->withInput();
            }
        }
    }

    public function edit($company_id)
    {

        try {
            $this->data['company'] = Company::findOrFail($company_id);
            return View::make('companies.edit', $this->data);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Session::flash('error_msg', trans('msgs.company_not_found'));
            return Redirect::back();
        }

    }

    public function update()
    {

        $id = Input::get('id');
        $name = Input::get('name');
        $description = Input::get('description', '');
        $domain = Input::get('domain');
        $old_logo = Input::get('old_logo');

        if (sizeof(Company::where('name', $name)->where('id', '!=', $id)->get()) > 0) {
            Session::flash('error_msg', trans('msgs.company_already_exists'));
            return Redirect::to('/companies/update/')->withInput();
        }

        if(Config::get('site-config.is_demo')&&$id==1){
            Session::flash('error_msg','Demo : Feature is disabled');
            return Redirect::to('/dashboard');
        }

        if (!$this->companyAddValidator->validate(Input::all())) {
            $messages = Utils::buildMessages($this->companyAddValidator->getValidation()->messages()->all());
            Session::flash('error_msg', $messages);
            return Redirect::to('/companies/update/' . $id)->withInput();
        } else {
            try {
                $company = Company::findOrFail($id);

                $company->name = $name;
                $company->description = $description;
                $company->domain = $domain;
                $company->user_id = Auth::user()->id;
                $company->logo = Input::hasFile('logo') ? Utils::imageUpload(Input::file('logo'), 'companies') : $old_logo;
                $company->save();

                Session::flash('success_msg', trans('msgs.company_updated_success'));
                return Redirect::to('/companies/all')->withInput();

            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                Session::flash('error_msg', trans('msgs.unable_to_update_company'));
                return Redirect::to('/companies/update/' . $id)->withInput();
            }
        }
    }

    public function delete($company_id)
    {
        $departments = Department::where('company_id',$company_id)->get();

        if(Config::get('site-config.is_demo')&&$company_id==1){
            Session::flash('error_msg','Demo : Feature is disabled');
            return Redirect::to('/dashboard');
        }

        foreach($departments as $department) {

            if (!empty($department)) {

                $tickets = Tickets::where('department_id', $department->id)->get();

                //Delete tickets
                foreach ($tickets as $ticket) {
                    TicketAttachments::where('thread_id', $ticket->id)->delete();
                    MessageThread::where('id', $ticket->thread_id)->delete();
                    ThreadMessages::where('thread_id', $ticket->thread_id)->delete();
                }

                Tickets::where('department_id', $department->id)->delete();

                //Delete Chat and Conversations
                $online_users = OnlineUsers::where('department_id', $department->id)->get();

                foreach ($online_users as $online_user) {
                    MessageThread::where('id', $online_user->thread_id)->delete();
                    ThreadMessages::where('thread_id', $online_user->thread_id)->delete();
                }

                OnlineUsers::where('department_id', $department->id)->delete();

                $closed_conversations = ClosedConversations::where('department_id', $department->id)->get();

                foreach ($closed_conversations as $closed_conversation) {
                    MessageThread::where('id', $closed_conversation->thread_id)->delete();
                    ThreadMessages::where('thread_id', $closed_conversation->thread_id)->delete();
                }

                ClosedConversations::where('department_id', $department->id)->delete();

                $operators = OperatorsDepartment::where('department_id',$department->id)->lists('user_id');

                if(sizeof($operators)>0) {
                    User::whereIn('id', $operators)->delete();
                    UsersGroups::whereIn('user_id', $operators)->delete();
                }

                OperatorsDepartment::where('department_id',$department->id)->delete();

                $department_admin = DepartmentAdmins::where('department_id', $department->id)->first();

                if (!empty($department_admin)) {
                    UsersGroups::where('user_id', $department_admin->user_id)->delete();
                    User::where("id", $department_admin->user_id)->delete();
                    CompanyDepartmentAdmins::where("user_id", $department_admin->user_id)->delete();
                    CannedMessages::where('operator_id',$operators)->delete();
                }

            }

            DepartmentAdmins::where('department_id', $department->id)->delete();

            Department::where('id', $department->id)->delete();
        }

        $company = Company::where('id',$company_id)->first();

        RecentActivities::createActivity("Company '".$company->name."' deleted by User '".Auth::user()->name."' ID '".Auth::user()->id."' ");

        Company::where('id',$company_id)->delete();

        Session::flash('success_msg', trans('msgs.company_deleted_success'));

        return Redirect::to('/companies/all');
    }

    public function all()
    {
        $this->data['companies'] = Company::all();
        $this->data['permissions'] = Permissions::all();
        return View::make('companies.all', $this->data);
    }
}