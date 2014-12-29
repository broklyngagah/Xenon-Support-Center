<?php

class CustomersTicketsController extends BaseController
{

    public $ticketMailer;

    function __construct(\KodeInfo\Mailers\TicketsMailer $ticketMailer)
    {
        $this->ticketMailer = $ticketMailer;

        if (!\KodeInfo\Utilities\Utils::isCustomer(Auth::user()->id)) {
            Session::flash('error_msg',trans('msgs.access_denied'));
            return Redirect::to('/dashboard');
        }

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

        }elseif (\KodeInfo\Utilities\Utils::isCustomer(Auth::user()->id)) {

            $company_customer = CompanyCustomers::where('customer_id', Auth::user()->id)->first();
            $this->data['company'] = Company::where('id', $company_customer->company_id)->first();
            $this->data["operator"] = User::where('id', Auth::user()->id)->first();

            $this->data['departments'] = Department::where('company_id', $company_customer->company_id)->get();;

        } else {

            $companies = Company::all();

            if (sizeof($companies) > 0) {
                $departments = Department::where('company_id', $companies[0]->id)->get();
            } else {
                $departments = [];
            }

            $this->data['companies'] = $companies;
            $this->data['departments'] = $departments;

        }


        return View::make('tickets.create', $this->data);
    }


    public function getStatusTickets($customer_id, $status)
    {

        if ($status == "all") {
            $tickets = Tickets::orderBy('priority', 'desc')->where('customer_id', $customer_id)->get();
        } else {
            $tickets = Tickets::orderBy('priority', 'desc')->where('customer_id', $customer_id)->where('status', $status)->get();
        }

        foreach ($tickets as $ticket) {
            $ticket->customer = User::where('id', $ticket->customer_id)->first();
            $ticket->company = Company::where('id', $ticket->company_id)->first();
            $ticket->department = Department::where('id', $ticket->department_id)->first();

            if ($ticket->operator_id > 0) {
                $ticket->operator = User::where('id', $ticket->operator_id)->first();
            }
        }

        $this->data['tickets_all_str'] = View::make("tickets.stub-all-tickets", ['tickets' => $tickets])->render();

        return View::make('tickets.customer_view', $this->data);

    }

    public function store()
    {

        $v = Validator::make(["name" => Input::get('name'), "email" => Input::get('email'),
            "priority" => Input::get('priority'), "company" => Input::get('company'),
            "department" => Input::get('department'), "attachment" => Input::get('attachment'),
            "subject" => Input::get('subject'), "description" => Input::get('description')],
            ["name" => 'required', "email" => 'required|email', "priority" => 'required',
                "company" => 'required', "department" => 'required', "description" => 'required',
                "attachment" => 'mimes:rar,zip|size:10000', "subject" => 'required']);

        if ($v->passes()) {

            $company_customer_ids = CompanyCustomers::where('company_id', Input::get('company'))->lists('customer_id');

            if (sizeof($company_customer_ids) > 0) {
                $user = User::where('email', Input::get('email'))->first();
            } else {
                $user = null;
            }

            if (!empty($user) && !is_null($user)) {

                $user = User::where('email', Input::get('email'))->first();

                if (!empty($user)) {
                    $company_customer = new CompanyCustomers();
                    $company_customer->company_id = Input::get('company');
                    $company_customer->customer_id = $user->id;
                    $company_customer->save();
                }

            } else {

                $password = Str::random();

                $userManager = new \KodeInfo\UserManagement\UserManagement();

                $user = $userManager->createUser(["name" => Input::get('name'),
                    "email" => Input::get('email'),
                    "password" => $password,
                    "password_confirmation" => $password],
                    'customer',
                    false);

                $user->avatar = "/assets/img/default-avatar.jpg";
                $user->save();

                $company_customer = new CompanyCustomers();
                $company_customer->company_id = Input::get('company');
                $company_customer->customer_id = $user->id;
                $company_customer->save();
            }

            $repo = new KodeInfo\Repo\MessageRepo();
            $thread = $repo->createNewThread($user->id, Input::get("description"), true);

            $ticket = new Tickets();
            $ticket->thread_id = $thread['thread_id'];
            $ticket->customer_id = $user->id;
            $ticket->priority = Input::get("priority");
            $ticket->company_id = Input::get("company");
            $ticket->department_id = Input::get("department");
            $ticket->subject = Input::get("subject");
            $ticket->description = Input::get("description");
            $ticket->status = Tickets::TICKET_NEW;
            $ticket->requested_on = \Carbon\Carbon::now();
            $ticket->save();

            $ticket_attachment = new TicketAttachments();
            $ticket_attachment->thread_id = $thread['thread_id'];
            $ticket_attachment->message_id = $thread['msg_id'];
            $ticket_attachment->has_attachment = Input::hasFile('attachment');
            $ticket_attachment->attachment_path = Input::hasFile('attachment') ? Utils::fileUpload(Input::file('attachment'), 'attachments') : '';
            $ticket_attachment->save();

            $country = DB::table('countries')->where('countryCode', Input::get('country'))->first();

            $geo_info = new ThreadGeoInfo();
            $geo_info->thread_id = $thread['thread_id'];
            $geo_info->ip_address = Input::get('ip');
            $geo_info->country_code = Input::get('country');
            $geo_info->country = !empty($country) ? $country->countryName : "";
            $geo_info->provider = Input::get('provider');
            $geo_info->current_page = "";
            $geo_info->all_pages = "";
            $geo_info->save();

            $customer = User::find($ticket->customer_id);

            $mailer_extra = [
                'ticket' => $ticket,
                'has_attachment' => $ticket_attachment->has_attachment,
                'attachment_path' => $ticket_attachment->attachment_path
            ];

            $this->ticketMailer->created($customer->email, $customer->name, $mailer_extra);

            Session::flash('success_msg', trans('msgs.ticket_created_success'));
            return Redirect::to('/tickets/all');

        } else {
            Session::flash('error_msg', Utils::buildMessages($v->messages()->all()));
            return Redirect::back()->withInput(Input::except('attachment'));
        }

    }

    public function pending()
    {

        $tickets = Tickets::orderBy('priority', 'desc')->where('customer_id',Auth::user()->id)->where('status',Tickets::TICKET_PENDING)->get();

        foreach ($tickets as $ticket) {
            $ticket->customer = User::where('id', $ticket->customer_id)->first();
            $ticket->company = Company::where('id', $ticket->company_id)->first();
            $ticket->department = Department::where('id', $ticket->department_id)->first();

            if ($ticket->operator_id > 0) {
                $ticket->operator = User::where('id', $ticket->operator_id)->first();
            }
        }

        $this->data['tickets'] = $tickets;

        return View::make('tickets.customers_all', $this->data);
    }

    public function resolved()
    {

        $tickets = Tickets::orderBy('priority', 'desc')->where('customer_id',Auth::user()->id)->where('status',Tickets::TICKET_RESOLVED)->get();

        foreach ($tickets as $ticket) {
            $ticket->customer = User::where('id', $ticket->customer_id)->first();
            $ticket->company = Company::where('id', $ticket->company_id)->first();
            $ticket->department = Department::where('id', $ticket->department_id)->first();

            if ($ticket->operator_id > 0) {
                $ticket->operator = User::where('id', $ticket->operator_id)->first();
            }
        }

        $this->data['tickets'] = $tickets;

        return View::make('tickets.customers_all', $this->data);
    }

    public function all()
    {

        $tickets = Tickets::orderBy('priority', 'desc')->where('customer_id',Auth::user()->id)->get();

        foreach ($tickets as $ticket) {
            $ticket->customer = User::where('id', $ticket->customer_id)->first();
            $ticket->company = Company::where('id', $ticket->company_id)->first();
            $ticket->department = Department::where('id', $ticket->department_id)->first();

            if ($ticket->operator_id > 0) {
                $ticket->operator = User::where('id', $ticket->operator_id)->first();
            }
        }

        $this->data['tickets'] = $tickets;

        return View::make('tickets.customers_all', $this->data);
    }

    public function read($thread_id)
    {

        if (Utils::isOperator(Auth::user()->id)) {
            $canned_messages = CannedMessages::where('operator_id', Auth::user()->id)->get();
        }elseif (Utils::isDepartmentAdmin(Auth::user()->id)) {

            $department_admin = DepartmentAdmins::where('user_id',Auth::user()->id)->first();
            $operator_ids = OperatorsDepartment::where('department_id',$department_admin->department_id)->lists("user_id");

            if(sizeof($operator_ids)>0)
                $canned_messages = CannedMessages::whereIn('operator_id', $operator_ids)->get();
            else
                $canned_messages = [];

        }else{
            $canned_messages = CannedMessages::all();
        }

        $this->data['canned_messages'] = $canned_messages;

        $ticket = Tickets::where('thread_id', $thread_id)->first();

        $thread = MessageThread::find($thread_id);

        if ($ticket->customer_id > 0) {
            $ticket->customer = User::find($ticket->customer_id);
        }

        $messages = MessageThread::getTicketMessages($thread_id, 0);

        $geo_info = ThreadGeoInfo::where('thread_id', $thread_id)->first();
        $this->data['geo'] = $geo_info;
        $this->data['message_str'] = $messages["messages_str"];
        $this->data['last_message_id'] = $messages["last_message_id"];
        $this->data['thread'] = $thread;
        $this->data['ticket'] = $ticket;

        return View::make('tickets.customers_read', $this->data);
    }

    public static function getTicketMessages()
    {

        //Check any operators online from company_id
        //Check if we have any messages from user_id , thread_id
        $v = Validator::make(["user_id" => Input::get('user_id'), "thread_id" => Input::get('thread_id')
            , "last_message_id" => Input::get('last_message_id')],
            ["user_id" => 'required', "thread_id" => 'required', "last_message_id" => 'required']);

        if ($v->passes()) {

            $thread_geo_info = ThreadGeoInfo::where('thread_id', Input::get('thread_id'))->first();
            $response['geo'] = $thread_geo_info;

            $response['ticket'] = Tickets::where('thread_id', Input::get('thread_id'))->first();

            $response['messages'] = MessageThread::getTicketMessages(Input::get('thread_id'), Input::get('last_message_id'));

            return json_encode($response);
        }

    }

    public function update()
    {

        $v = Validator::make(["thread_id" => Input::get('thread_id'), "user_id" => Input::get('user_id'),
            "message" => Input::get('message'), "status" => Input::get('status'), 'attachment' => Input::get('attachment')],
            ["thread_id" => 'required', "user_id" => 'required', "message" => 'required',
                "status" => 'required', "attachment" => 'mimes:rar,zip|size:10000']);


        if ($v->passes()) {

            $ticket = Tickets::where('thread_id',Input::get('thread_id'))->first();
            $ticket->thread_id = Input::get('thread_id');
            $ticket->status = Input::get('status');
            $ticket->save();

            $thread_message = new ThreadMessages();
            $thread_message->thread_id = Input::get('thread_id');
            $thread_message->sender_id = Input::get('user_id');
            $thread_message->message = Input::get('message');
            $thread_message->save();

            $ticket_attachment = new TicketAttachments();
            $ticket_attachment->thread_id = Input::get('thread_id');
            $ticket_attachment->message_id = $thread_message->id;
            $ticket_attachment->has_attachment = Input::hasFile('attachment');
            $ticket_attachment->attachment_path = Input::hasFile('attachment') ? Utils::fileUpload(Input::file('attachment'), 'attachments') : '';
            $ticket_attachment->save();

            if (!Utils::isBackendUser(Input::get('user_id'))) {
                $country = DB::table('countries')->where('countryCode', Input::get('country'))->first();
                $geo_info = ThreadGeoInfo::where('thread_id', Input::get('thread_id'))->first();
                $geo_info->ip_address = Input::get('ip', $geo_info->ip_address);
                $geo_info->country_code = Input::get('country', $geo_info->country_code);
                $geo_info->country = !empty($country) ? $country->countryName : "";
                $geo_info->provider = Input::get('provider');
                $geo_info->current_page = "";
                $geo_info->all_pages = "";
                $geo_info->save();
            }


            $customer = User::find($ticket->customer_id);

            $raw_settings = Settings::where('key', 'tickets')->pluck('value');
            $decode_settings = json_decode($raw_settings);

            if ($decode_settings->should_send_email_ticket_reply) {

                if (!$customer->is_online) {

                    $mailer_extra = [
                        'ticket' => $ticket,
                        'has_attachment' => $ticket_attachment->has_attachment,
                        'attachment_path' => $ticket_attachment->attachment_path,
                        'operator_message' => $thread_message
                    ];

                    $this->ticketMailer->updated($customer->email, $customer->name, $mailer_extra);
                }

            } else {

                $mailer_extra = [
                    'ticket' => $ticket,
                    'has_attachment' => $ticket_attachment->has_attachment,
                    'attachment_path' => $ticket_attachment->attachment_path,
                    'operator_message' => $thread_message
                ];

                $this->ticketMailer->updated($customer->email, $customer->name, $mailer_extra);

            }


            return json_encode(['result' => 1, 'errors' => trans('msgs.ticket_updated_success')]);

        } else {
            return json_encode(['result' => 0, 'errors' => \KodeInfo\Utilities\Utils::buildMessages($v->messages()->all())]);
        }
    }

    public function delete($thread_id)
    {
        Tickets::where('thread_id', $thread_id)->delete();
        TicketAttachments::where('thread_id', $thread_id)->delete();
        MessageThread::where('id', $thread_id)->delete();
        ThreadMessages::where('thread_id', $thread_id)->delete();
        ThreadGeoInfo::where('thread_id', $thread_id)->delete();

        Session::flash('success_msg', trans('msgs.ticket_deleted_success'));
        return Redirect::to('/tickets/all');
    }

}