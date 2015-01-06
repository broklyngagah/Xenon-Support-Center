<?php

class DashboardController extends BaseController
{

    function getSetup()
    {
        return View::make("setup");
    }

    function postSetup()
    {

        $con = null;

        try {
            $con = mysqli_connect(Input::get('db_host'), Input::get('db_user'), Input::get('db_password'), Input::get('db_name'));

            if (!is_null($con) && is_object($con)) {

                $db_content = "<?php
                return [
                    'fetch' => PDO::FETCH_CLASS,
	                'default' => 'mysql',
	                'connections' => [
		                'mysql' => [
			                'driver'    => 'mysql',
			                'host'      => '" . Input::get('db_host') . "',
			                'database'  => '" . Input::get('db_name') . "',
			                'username'  => '" . Input::get('db_user') . "',
			                'password'  => '" . Input::get('db_password') . "',
			                'charset'   => 'utf8',
			                'collation' => 'utf8_unicode_ci',
			                'prefix'    => '',
		                ]
	                ],
	                'migrations' => 'migrations',
                ];";

                \File::put(app_path() . "/config/database.php", $db_content);

                //exec("php artisan migrate");
                //exec("php artisan db:seed");
                //exec("chgrp -R www-data /your-uploaded-folder");
                //exec("php artisan db:seed");
                //exec("php artisan db:seed");

                Session::flash('success_msg', 'Setup is successfully . Please login below');
                return Redirect::to("/login");

            } else {
                Session::flash('error_msg', 'Unable to connect . check database settings');
                return Redirect::back()->withInput(Input::all());
            }

        } catch (Exception $e) {
            Session::flash('error_msg', 'Unable to connect . check database settings');
            return Redirect::back()->withInput(Input::all());
        }
    }

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

    public function cleanDB()
    {

        //Not truncating countries , migrations , permissions , settings

        DB::table('blocking')->truncate();
        DB::table('canned_messages')->truncate();
        DB::table('closed_conversations')->truncate();
        DB::table('companies')->truncate();
        DB::table('company_customers')->truncate();
        DB::table('company_department_admins')->truncate();
        DB::table('departments')->truncate();
        DB::table('department_admins')->truncate();
        DB::table('groups')->truncate();
        DB::table('message_threads')->truncate();
        DB::table('online_users')->truncate();
        DB::table('operators_department')->truncate();
        DB::table('paired_templates')->truncate();
        DB::table('thread_geo_info')->truncate();
        DB::table('thread_messages')->truncate();
        DB::table('throttle')->truncate();
        DB::table('tickets')->truncate();
        DB::table('tickets_attachment')->truncate();
        DB::table('translations')->truncate();
        DB::table('users')->truncate();
        DB::table('users_groups')->truncate();
        //DB::table('permissions')->truncate();

        /*
        DB::statement("INSERT INTO `permissions` (`id`, `key`, `text`, `created_at`, `updated_at`) VALUES
(1, 'tickets.create', 'Operator/Department admin/Customer can create tickets', '2014-12-28 03:30:28', '2014-12-28 03:30:28'),
(2, 'tickets.edit', 'Operator/Department admin/Customer can reply to ticket', '2014-12-28 03:30:43', '2014-12-28 03:30:43'),
(3, 'tickets.all', 'Operator/Department admin/Customer can see all tickets , if you assigned anyone tickets.create , tickets.edit , tickets.delete then assigning this permission is mandatory so they can view ticket', '2014-12-28 03:31:02', '2014-12-28 03:31:02'),
(4, 'tickets.delete', 'Operator/Department admin/Customer can delete ticket', '2014-12-28 03:31:17', '2014-12-28 03:31:17'),
(5, 'customers.create', 'Operator/Department admin/Customer can create a new customer', '2014-12-28 03:31:58', '2014-12-28 03:31:58'),
(6, 'customers.edit', 'Operator/Department admin/Customer can edit customer', '2014-12-28 03:32:16', '2014-12-28 03:32:16'),
(7, 'customers.all', 'Operator/Department admin/Customer can view all customers , if you assigned anyone customers.create , customers.edit , customers.delete then assigning this permission is mandatory so they can view customer', '2014-12-28 03:32:30', '2014-12-28 03:32:30'),
(8, 'customers.delete', 'Operator/Department admin/Customer can delete customer', '2014-12-28 03:32:46', '2014-12-28 03:32:46'),
(9, 'operators.create', 'Operator/Department admin/Customer can create operator', '2014-12-28 03:33:02', '2014-12-28 03:33:02'),
(10, 'operators.edit', 'Operator/Department admin/Customer can edit operator', '2014-12-28 03:33:18', '2014-12-28 03:33:18'),
(11, 'operators.activate', 'Operator/Department admin/Customer can activate operators', '2014-12-28 03:33:33', '2014-12-28 03:33:33'),
(12, 'operators.delete', 'Operator/Department admin/Customer can delete operator', '2014-12-28 03:33:51', '2014-12-28 03:33:51'),
(13, 'operators.all', 'Operator/Department admin/Customer can view all operators , if you assigned anyone operators.create , operators.edit , operators.delete then assigning this permission is mandatory so they can view operator', '2014-12-28 03:34:08', '2014-12-28 03:34:08'),
(14, 'departments.create', 'Operator/Department admin/Customer can create department', '2014-12-28 03:34:25', '2014-12-28 03:34:25'),
(15, 'departments.edit', 'Operator/Department admin/Customer can edit department', '2014-12-28 03:34:39', '2014-12-28 03:34:39'),
(16, 'departments.all', 'Operator/Department admin/Customer can view all departments , if you assigned anyone departments.create , departments.edit , departments.delete then assigning this permission is mandatory so they can view department', '2014-12-28 03:35:22', '2014-12-28 03:35:22'),
(17, 'departments.delete', 'Operator/Department admin/Customer can delete department', '2014-12-28 03:35:41', '2014-12-28 03:35:41'),
(18, 'companies.create', 'Operator/Department admin/Customer can create company', '2014-12-28 03:35:58', '2014-12-28 03:35:58'),
(19, 'companies.edit', 'Operator/Department admin/Customer can edit company', '2014-12-28 03:36:13', '2014-12-28 03:36:13'),
(20, 'companies.all', 'Operator/Department admin/Customer can view all companies , if you assigned anyone companies.create , companies.edit , companies.delete then assigning this permission is mandatory so they can view company', '2014-12-28 03:36:28', '2014-12-28 03:36:28'),
(21, 'companies.delete', 'Operator/Department admin/Customer can delete company', '2014-12-28 03:36:46', '2014-12-28 03:36:46'),
(22, 'canned_messages.create', 'Operator/Department admin/Customer can create canned messages', '2014-12-28 03:37:05', '2014-12-28 03:37:05'),
(23, 'canned_messages.edit', 'Operator/Department admin/Customer can edit canned messages', '2014-12-28 03:37:23', '2014-12-28 03:37:23'),
(24, 'canned_messages.all', 'Operator/Department admin/Customer can view all canned messages , if you assigned anyone canned_messages.create , canned_messages.edit , canned_messages.delete then assigning this permission is mandatory so they can view canned message', '2014-12-28 03:38:31', '2014-12-28 03:38:31'),
(25, 'canned_messages.delete', 'Operator/Department admin/Customer can delete canned messages', '2014-12-28 03:38:46', '2014-12-28 03:38:46'),
(26, 'conversations.accept', 'Operator/Department admin/Customer can accept new chats .', '2014-12-28 03:39:05', '2014-12-28 03:39:05'),
(27, 'conversations.close', 'Operator/Department admin/Customer can close conversations . conversations will be moved to closed conversations', '2014-12-28 03:39:24', '2014-12-28 03:39:24'),
(28, 'conversations.delete', 'Operator/Department admin/Customer can delete closed conversations . conversations will be permanently deleted', '2014-12-28 03:40:33', '2014-12-28 03:40:33'),
(29, 'conversations.closed', 'Operator/Department admin/Customer can view closed conversations .', '2014-12-28 03:40:49', '2014-12-28 03:40:49'),
(30, 'mailchimp.pair_email', 'Operator/Department admin/Customer can pair emails to templates .', '2014-12-28 03:41:03', '2014-12-28 03:41:03'),
(31, 'mailchimp.all', 'Operator/Department admin/Customer can view all mailchimp templates .', '2014-12-28 03:41:17', '2014-12-28 03:41:17'),
(32, 'mailchimp.delete', 'Operator/Department admin/Customer can delete paired templates .', '2014-12-28 03:41:31', '2014-12-28 03:41:31'),
(33, 'blocking.block', 'Operator/Department admin/Customer can block ip .', '2014-12-28 03:41:52', '2014-12-28 03:41:52'),
(34, 'blocking.all', 'Operator/Department admin/Customer can view all blocked ip''s , if you assigned anyone blocking.delete then assigning this permission is mandatory so they can view blocked ip', '2014-12-28 03:42:12', '2014-12-28 03:42:12'),
(35, 'blocking.delete', 'Operator/Department admin/Customer can remove blocked ip .', '2014-12-28 03:42:27', '2014-12-28 03:42:27'),
(36, 'settings.all', 'Operator/Department admin/Customer can change settings which includes smtp , mailchimp , mailgun configurations .', '2014-12-28 03:42:44', '2014-12-28 03:42:44'),
(37, 'departments_admins.create', 'Operator/Department admin/Customer can create department admin .', '2014-12-28 03:43:04', '2014-12-28 03:43:04'),
(38, 'departments_admins.edit', 'Operator/Department admin/Customer can edit department admin .', '2014-12-28 03:43:24', '2014-12-28 03:43:24'),
(39, 'departments_admins.remove', 'Operator/Department admin/Customer can remove department admin .', '2014-12-28 03:43:42', '2014-12-28 03:43:42'),
(40, 'departments_admins.activate', 'Operator/Department admin/Customer can activate department admin .', '2014-12-28 03:44:00', '2014-12-28 03:44:00'),
(41, 'departments_admins.all', 'Operator/Department admin/Customer can view all department admins , if you assigned anyone departments_admins.create , departments_admins.edit , departments_admins.delete then assigning this permission is mandatory so they can view department admin', '2014-12-28 03:44:15', '2014-12-28 03:44:15'),
(42, 'departments_admins.delete', 'Operator/Department admin/Customer can delete department admin .', '2014-12-28 03:44:34', '2014-12-28 03:44:34')"
        );
*/


        $group = new Groups();
        $group->name = "admin";
        $group->save();

        $group = new Groups();
        $group->name = "department-admin";
        $group->save();

        $group = new Groups();
        $group->name = "operator";
        $group->save();

        $group = new Groups();
        $group->name = "customer";
        $group->save();

        $admin = new User();
        $admin->name = "Admin";
        $admin->email = "admin@mail.com";
        $admin->password = Hash::make("admin");
        $admin->avatar = "/assets/images/default-avatar.jpg";
        $admin->show_avatar = 1;
        $admin->birthday = "01-01-1990";
        $admin->bio = "This is bio";
        $admin->gender = "Male";
        $admin->mobile_no = "1111111111";
        $admin->country = "India";
        $admin->activated = 1;
        $admin->activated_at = \Carbon\Carbon::now();
        $admin->save();

        DB::table('users_groups')->insert(['user_id' => 1, 'group_id' => 1]);

        return 'Success';

    }

}