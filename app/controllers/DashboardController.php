<?php

class DashboardController extends BaseController
{

    function index()
    {

        $past_hr = \Carbon\Carbon::now()->subHour();
        $today = \Carbon\Carbon::now()->subDay();
        $this_week = \Carbon\Carbon::now()->subWeek();
        $this_month = \Carbon\Carbon::now()->subMonth();

        if (\KodeInfo\Utilities\Utils::isDepartmentAdmin(Auth::user()->id)) {

            $department_admin = DepartmentAdmins::where('user_id', Auth::user()->id)->first();
            $department = Department::where('id', $department_admin->department_id)->first();
            $company = Company::where('id', $department->company_id)->first();

            $this->data['tickets_past_hr'] = DB::table('tickets')->where('requested_on', '>', $past_hr)->where('company_id', $company->id)->where('department_id', $department->id)->count();
            $this->data['tickets_today'] = DB::table('tickets')->where('requested_on', '>', $today)->where('company_id', $company->id)->where('department_id', $department->id)->count();
            $this->data['tickets_this_week'] = DB::table('tickets')->where('requested_on', '>', $this_week)->where('company_id', $company->id)->where('department_id', $department->id)->count();
            $this->data['tickets_this_month'] = DB::table('tickets')->where('requested_on', '>', $this_month)->where('company_id', $company->id)->where('department_id', $department->id)->count();
            $this->data['tickets_total'] = sizeof(DB::table('tickets')->get());

            $department->all_tickets = sizeof(DB::table('tickets')->where('department_id', $department->id)->get());
            $department->pending_tickets = sizeof(DB::table('tickets')->where('status', Tickets::TICKET_PENDING)->where('department_id', $department->id)->get());
            $department->resolved_tickets = sizeof(DB::table('tickets')->where('status', Tickets::TICKET_RESOLVED)->where('department_id', $department->id)->get());

            $operator_ids = OperatorsDepartment::where('department_id', $department->id)->lists('user_id');

            if (sizeof($operator_ids) > 0) {
                $department->operators_online = sizeof(User::whereIn('id', $operator_ids)->where("is_online", 1)->get());
                $department->operators_offline = sizeof(User::whereIn('id', $operator_ids)->where("is_online", 0)->get());
            } else {
                $department->operators_online = 0;
                $department->operators_offline = 0;
            }

            $this->data['department_stats'] = $department;


        } elseif (\KodeInfo\Utilities\Utils::isOperator(Auth::user()->id)) {

            $department_operator = OperatorsDepartment::where('user_id', Auth::user()->id)->first();
            $department = Department::where('id', $department_operator->department_id)->first();
            $company = Company::where('id', $department->company_id)->first();

            $this->data['tickets_past_hr'] = DB::table('tickets')->where('requested_on', '>', $past_hr)->where('company_id', $company->id)->where('department_id', $department->id)->count();
            $this->data['tickets_today'] = DB::table('tickets')->where('requested_on', '>', $today)->where('company_id', $company->id)->where('department_id', $department->id)->count();
            $this->data['tickets_this_week'] = DB::table('tickets')->where('requested_on', '>', $this_week)->where('company_id', $company->id)->where('department_id', $department->id)->count();
            $this->data['tickets_this_month'] = DB::table('tickets')->where('requested_on', '>', $this_month)->where('company_id', $company->id)->where('department_id', $department->id)->count();
            $this->data['tickets_total'] = sizeof(DB::table('tickets')->get());

            $department->all_tickets = sizeof(DB::table('tickets')->where('department_id', $department->id)->get());
            $department->pending_tickets = sizeof(DB::table('tickets')->where('status', Tickets::TICKET_PENDING)->where('department_id', $department->id)->get());
            $department->resolved_tickets = sizeof(DB::table('tickets')->where('status', Tickets::TICKET_RESOLVED)->where('department_id', $department->id)->get());

            $operator_ids = OperatorsDepartment::where('department_id', $department->id)->lists('user_id');

            if (sizeof($operator_ids) > 0) {
                $department->operators_online = sizeof(User::whereIn('id', $operator_ids)->where("is_online", 1)->get());
                $department->operators_offline = sizeof(User::whereIn('id', $operator_ids)->where("is_online", 0)->get());
            } else {
                $department->operators_online = 0;
                $department->operators_offline = 0;
            }

            $this->data['department_stats'] = $department;

        } else {

            $this->data['tickets_past_hr'] = DB::table('tickets')->where('requested_on', '>', $past_hr)->count();
            $this->data['tickets_today'] = DB::table('tickets')->where('requested_on', '>', $today)->count();
            $this->data['tickets_this_week'] = DB::table('tickets')->where('requested_on', '>', $this_week)->count();
            $this->data['tickets_this_month'] = DB::table('tickets')->where('requested_on', '>', $this_month)->count();
            $this->data['tickets_total'] = sizeof(DB::table('tickets')->get());

            $companies = Company::all();

            foreach ($companies as $company) {

                $departments = Department::where('company_id', $company->id)->get();

                foreach ($departments as $department) {
                    $department->all_tickets = sizeof(DB::table('tickets')->where('department_id', $department->id)->get());
                    $department->pending_tickets = sizeof(DB::table('tickets')->where('status', Tickets::TICKET_PENDING)->where('department_id', $department->id)->get());
                    $department->resolved_tickets = sizeof(DB::table('tickets')->where('status', Tickets::TICKET_RESOLVED)->where('department_id', $department->id)->get());

                    $operator_ids = OperatorsDepartment::where('department_id', $department->id)->lists('user_id');

                    if (sizeof($operator_ids) > 0) {
                        $department->operators_online = sizeof(User::whereIn('id', $operator_ids)->where("is_online", 1)->get());
                        $department->operators_offline = sizeof(User::whereIn('id', $operator_ids)->where("is_online", 0)->get());
                    } else {
                        $department->operators_online = 0;
                        $department->operators_offline = 0;
                    }
                }

                $company->departments = $departments;
            }

            $this->data['department_stats'] = $companies;
        }

        return View::make('index', $this->data);
    }


}