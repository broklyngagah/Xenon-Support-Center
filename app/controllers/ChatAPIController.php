<?php

use KodeInfo\UserManagement\UserManagement;
use KodeInfo\Mailers\UsersMailer;

class ChatAPIController extends BaseController {

    public $mailer;

    function __construct(UserManagement $userManager,UsersMailer $mailer){
        $this->userManager = $userManager;
        $this->mailer = $mailer;
    }

    public function send($arr){
        $response = Response::json($arr);
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With');
        $response->header('Access-Control-Allow-Credentials', 'true');
        return $response;
    }

    public function init(){

        if(Input::has('ip_address')){

            //check ip_address in blocking table
            $blocking = Blocking::where('ip_address',Input::get('ip_address'))->first();

            if(!empty($blocking)){
                if($blocking->should_block_chat || $blocking->should_block_web_access){
                   return $this->send(['blocked'=>1,'errors'=>trans('msgs.your_ip_blocked_by_admin_contact_support')]);
                }else{
                    return $this->send(['blocked'=>0,'data'=>$this->init_data()]);
                }
            }else{
                return $this->send(['blocked'=>0,'data'=>$this->init_data()]);
            }

        }else{
            return $this->send(['blocked'=>1,'errors'=> trans('msgs.unable_to_initialize_chat')]);
        }

    }

    public function sendMessage(){

        $v_data = [
            "thread_id" => Input::get('thread_id'),
            "user_id" => Input::get('user_id'),
            "message" => Input::get('message')
        ];

        $v_rules = [
            "thread_id" => 'required',
            "user_id" => 'required',
            "message" => 'required'
        ];

        $v = Validator::make($v_data,$v_rules);

        if($v->passes()&&Input::get("user_id")>0&&Input::get("thread_id")>0){
            $thread_message = new ThreadMessages();
            $thread_message->thread_id = Input::get('thread_id');
            $thread_message->sender_id = Input::get('user_id');
            $thread_message->message = Input::get('message');
            $thread_message->save();
            return $this->send(["result"=>1]);
        }else{
            return $this->send(["result"=>0]);
        }
    }

    public function convertToTicket($thread_id,$msg_id,$user,$subject,$message,$department_id,$company_id){

        $ticket = new Tickets();
        $ticket->thread_id = $thread_id;
        $ticket->customer_id = $user->id;
        $ticket->priority = Tickets::PRIORITY_MEDIUM;
        $ticket->company_id = $company_id;
        $ticket->department_id = $department_id;
        $ticket->subject = $subject;
        $ticket->description = $message;
        $ticket->status = Tickets::TICKET_NEW;
        $ticket->requested_on = \Carbon\Carbon::now();
        $ticket->save();

        $ticket_attachment = new TicketAttachments();
        $ticket_attachment->thread_id = $thread_id;
        $ticket_attachment->message_id = $msg_id;
        $ticket_attachment->has_attachment = Input::hasFile('attachment');
        $ticket_attachment->attachment_path = Input::hasFile('attachment')?Utils::fileUpload(Input::file('attachment'),'attachments'):'';
        $ticket_attachment->save();

        $customer = User::find($ticket->customer_id);

        $ticketMailer = new \KodeInfo\Mailers\TicketsMailer();
        $mailer_extra = Tickets::getCreatedFields(false,$ticket->id,$msg_id);
        $ticketMailer->created($customer->email,$customer->name,$mailer_extra);

    }

    public function start(){

        $response = [];

        $response['token'] = 0;

        if(Input::has('ip')){

            //check ip_address in blocking table
            $blocking = Blocking::where('ip_address',Input::get('ip'))->first();

            if(!empty($blocking)){
                if($blocking->should_block_chat || $blocking->should_block_web_access){
                    $response['blocked'] = 1;
                    $response['errors'] = trans('msgs.your_ip_blocked_by_admin_contact_support');
                }
            }

        }else{
            $response['blocked'] = 1;
            $response['errors'] = trans('msgs.your_ip_blocked_by_admin_contact_support');
        }

        $response['blocked'] = 0;

        $v_data = [
            "email" => Input::get('email'),
            "name" => Input::get('name'),
            "company_id" => Input::get('company_id'),
            "domain" => Input::get('domain'),
            "department" => Input::get('department'),
            "message" => Input::get('message'),
        ];

        $v_rules = [
            "email" => 'required|email',
            "name" => 'required',
            "company_id" => 'required',
            "domain" => 'required',
            "department" => 'required',
            "message" => 'required',
        ];

        $v = Validator::make($v_data,$v_rules);

        if($v->passes()){

            $request_check = Company::where('id',Input::get('company_id'))->where('domain',Input::get('domain'))->get();

            if(sizeof($request_check)<=0)
                return $this->send(['result'=>0,'errors'=>trans('msgs.invalid_request_check_id_and_domain')]);

            $company_customers = CompanyCustomers::where('company_id',Input::get('company_id'))->lists('customer_id');

            $user = null;

            if(sizeof($company_customers)) {
                $user = User::whereIn('id', $company_customers)->where('email', Input::get('email'))->first();
            }

            $operator_online = Company::operatorsOnline(Input::get('company_id'));
            $success_msg = trans('msgs.thanks_for_contacting_will_get_back');
            $response['is_online'] = $operator_online;

            $repo = new KodeInfo\Repo\MessageRepo();

            if(!empty($user)&&!is_null($user)){
                //user exists
                $count = OnlineUsers::where('user_id',$user->id)->first();
                if(sizeof($count)>0){
                    //user already online
                    $token = OnlineUsers::getToken();

                    if($operator_online>0) {
                        $response['token'] = $token;
                    }

                    $response['result'] = 1;
                    $response['user_id'] = $user->id;
                    $response['thread_id'] = $count->thread_id;
                    $response['success_msg'] = $success_msg;

                    return $this->send($response);
                }else{

                    $token = OnlineUsers::getToken();

                    $thread = $repo->createNewThread($user->id,Input::get("message"),true);

                    if($response['is_online']) {

                        $online_user = new OnlineUsers();
                        $online_user->user_id = $user->id;
                        $online_user->thread_id = $thread['thread_id'];
                        $online_user->operator_id = 0;
                        $online_user->company_id = Input::get('company_id');
                        $online_user->department_id = Input::get('department');
                        $online_user->locked_by_operator = 0;
                        $online_user->requested_on = \Carbon\Carbon::now();
                        $online_user->token = $token;
                        $online_user->save();
                    }

                    $country = DB::table('countries')->where('countryCode',Input::get('country'))->first();

                    $geo_info = new ThreadGeoInfo();
                    $geo_info->thread_id = $thread['thread_id'];
                    $geo_info->ip_address = Input::get('ip');
                    $geo_info->country_code = Input::get('country');
                    $geo_info->country = !empty($country)?$country->countryName:"";
                    $geo_info->provider = Input::get('provider');
                    $geo_info->current_page = Input::get('page');
                    $geo_info->all_pages = json_encode(['pages'=>[Input::get('page')]]);
                    $geo_info->save();

                    if(!$response['is_online']) {
                        $this->convertToTicket($thread['thread_id'],$thread['msg_id'],$user,"",Input::get('message'),Input::get('department'),Input::get('company_id'));
                    }

                    if($operator_online>0) {
                        $response['token'] = $token;
                    }

                    $response['result'] = 1;
                    $response['user_id'] = $user->id;
                    $response['thread_id'] = $thread['thread_id'];
                    $response['success_msg'] = $success_msg;

                    return $this->send($response);
                }
            }else{

                $password = Str::random();

                //Is user in users table then get id and put in company-customers table
                $user = User::where('email',Input::get('email'))->first();

                if(!empty($user)){
                    $company_customer = new CompanyCustomers();
                    $company_customer->company_id = Input::get('company_id');
                    $company_customer->customer_id = $user->id;
                    $company_customer->save();
                }else{

                    $user = $this->userManager->createUser(["name" => Input::get('name'),
                        "email" => Input::get('email'),
                        "password" => $password,
                        "password_confirmation" => $password],
                        'customer',
                        false);

                    $user->avatar = "/assets/images/default-avatar.jpg";
                    $user->save();

                    $user->getActivationCode();

                    $company_customer = new CompanyCustomers();
                    $company_customer->company_id = Input::get('company_id');
                    $company_customer->customer_id = $user->id;
                    $company_customer->save();

                    $this->mailer->welcome($user->email,$user->name,User::getWelcomeFields(false,$user->id,$password,$company_customer->company_id));

                    $this->mailer->activate($user->email,$user->name,User::getActivateFields(false,$user->id,$company_customer->company_id));
                }

                $token = OnlineUsers::getToken();

                $thread = $repo->createNewThread($user->id,Input::get("message"),true);

                if($response['is_online']) {

                    $online_user = new OnlineUsers();
                    $online_user->user_id = $user->id;
                    $online_user->thread_id = $thread['thread_id'];
                    $online_user->operator_id = 0;
                    $online_user->company_id = Input::get('company_id');
                    $online_user->department_id = Input::get('department');
                    $online_user->locked_by_operator = 0;
                    $online_user->requested_on = \Carbon\Carbon::now();
                    $online_user->token = $token;
                    $online_user->save();
                }

                $country = DB::table('countries')->where('countryCode',Input::get('country'))->first();

                $geo_info = new ThreadGeoInfo();
                $geo_info->thread_id = $thread['thread_id'];
                $geo_info->ip_address = Input::get('ip');
                $geo_info->country_code = Input::get('country');
                $geo_info->country = !empty($country)?$country->countryName:"";
                $geo_info->provider = Input::get('provider');
                $geo_info->current_page = Input::get('page');
                $geo_info->all_pages = json_encode(['pages'=>[Input::get('page')]]);
                $geo_info->save();

                if(!$response['is_online']) {
                    $this->convertToTicket($thread['thread_id'],$thread['msg_id'],$user,"",Input::get('message'),Input::get('department'),Input::get('company_id'));
                }

                if($operator_online>0) {
                    $response['token'] = $token;
                }

                $response['result'] = 1;
                $response['user_id'] = $user->id;
                $response['thread_id'] = $thread['thread_id'];
                $response['success_msg'] = $success_msg;

                return $this->send($response);

            }

        }else{
            return $this->send(['result'=>0,'errors'=>Utils::buildMessages($v->messages()->all())]);
        }


    }

    public function checkNewMessages()
    {
        $v_data = [
            "user_id" => Input::get('user_id'),
            "thread_id" => Input::get('thread_id'),
            "company_id" => Input::get('company_id'),
            "last_message_id" => Input::get('last_message_id')
        ];

        $v_rules = [
            "user_id" => 'required',
            "thread_id" => 'required',
            "company_id" => 'required',
            "last_message_id" => 'required'
        ];

        //Check any operators online from company_id
        //Check if we have any messages from user_id , thread_id
        $v = Validator::make($v_data,$v_rules);

        $response['is_online'] = false;

        if ($v->passes()) {

            //check any operator online
            $response['is_online'] = Company::operatorsOnline(Input::get('company_id'));
            $response['success_msg'] = "";

            $company = Company::find(Input::get('company_id'));

            $response['departments'] = Department::where('company_id',Input::get('company_id'))->get();

            foreach($response['departments'] as $department){

                $department_admin = DepartmentAdmins::where('department_id',$department->id)->first();

                $status = trans('msgs._offline_');

                $admin = User::where('id',$company->user_id)->first();

                if(!empty($admin)){
                    if($admin->is_online==1){
                        $status = trans('msgs._online_');
                    }
                }

                if(!empty($department_admin)){
                    $user = User::where('id',$department_admin->user_id)->first();
                    if(!empty($user)&&$user->is_online==1){
                        $status = trans('msgs._online_');
                    }
                }

                $operators = OperatorsDepartment::where('department_id',$department->id)->get();

                foreach($operators as $operator){

                    if(sizeof(User::where('id',$operator->user_id)->get())>0) {
                        $user = User::find($operator->user_id);
                        if ($user->is_online == 1) {
                            $status = trans('msgs._online_');
                        }
                    }
                }

                $department->name = $department->name.$status;

            }


            if(Input::has('token')&&sizeof(OnlineUsers::where('token',Input::get('token'))->get())>0){
                $token = Input::get('token');
                $response['token'] = $token;
                $online_user = OnlineUsers::where('token',$token)->first();
                $response['in_conversation'] = 1;
                $response['conversation_closed'] = 0;
                $response['thread_id'] = $online_user->thread_id;

                $thread_geo_info = ThreadGeoInfo::where('thread_id',$online_user->thread_id)->first();
                $this->fillPage(Input::get('page'),$thread_geo_info);

                $response['user_id'] = $online_user->user_id;
                $response['messages'] = MessageThread::getClientMessages($online_user->thread_id,Input::get('last_message_id'));
            }else{
                $response['in_conversation'] = 0;
                $response['token'] = "";
                $response['messages'] = [];
                $response['conversation_closed'] = 0;

                //Is conversation already closed
                if(sizeof(ClosedConversations::where('thread_id',Input::get('thread_id'))->get())>0){

                    $response['success_msg'] = trans('msgs.thanks_for_contacting_support');

                    $response['token'] = 0;
                    $response['in_conversation'] = 1;
                    $response['conversation_closed'] = 1;
                    $response['thread_id'] = 0;
                    $response['user_id'] = 0;

                }

            }


        }

        return $this->send($response);

    }

    public function fillPage($page,$geo){

        if(!empty($geo)){
            $geo->current_page = $page;
            $all_pages = json_decode($geo->all_pages);
            if(!in_array($page,$all_pages->pages)){
                array_push($all_pages->pages,$page);
                $geo->all_pages = json_encode($all_pages);
            }
            $geo->save();
        }

    }


    public function end(){
        if(!Input::has('thread_id')){
            return "";
        }

        $online_user = OnlineUsers::where('thread_id',Input::get('thread_id'))->first();

        $closed_conversation = new ClosedConversations();
        $closed_conversation->user_id = $online_user->user_id;
        $closed_conversation->thread_id = $online_user->thread_id;
        $closed_conversation->operator_id = $online_user->operator_id>0?$online_user->operator_id:0;
        $closed_conversation->company_id = $online_user->company_id;
        $closed_conversation->department_id = $online_user->department_id;
        $closed_conversation->requested_on = $online_user->requested_on;
        $closed_conversation->started_on = $online_user->started_on>0?$online_user->started_on:\Carbon\Carbon::now();
        $closed_conversation->token = $online_user->token;
        $closed_conversation->ended_on = \Carbon\Carbon::now();
        $closed_conversation->save();

        RecentActivities::createActivity("Chat Conversation closed #".$closed_conversation->id);

        OnlineUsers::where('thread_id',Input::get('thread_id'))->delete();

    }

    public function init_data(){
        $data = [];

        $data['in_conversation'] = 0;

        if(Input::has('token')&&sizeof(OnlineUsers::where('token',Input::get('token'))->get())>0){
            $data['in_conversation'] = 1;
        }

        $data['wrapper'] = View::make('conversations.stub-chat-wrapper')->render();

        return $data;
    }

}