<?php

use \KodeInfo\Repo\MessageRepo;

class ConversationsController extends BaseController
{

    public $repo;

    function __construct()
    {
        $this->repo = new MessageRepo();
        $this->beforeFilter('has_permission:conversations.accept', array('only' => array('accept')));
        $this->beforeFilter('has_permission:conversations.closed', array('only' => array('closedConversations')));
        $this->beforeFilter('has_permission:conversations.accept_close', array('only' => array('closeConversation')));
        $this->beforeFilter('has_permission:conversations.closed_delete', array('only' => array('deleteConversation')));
    }

    public function getServerMessages()
    {
        //Check any operators online from company_id
        //Check if we have any messages from user_id , thread_id
        $v = Validator::make(["user_id" => Input::get('user_id'), "thread_id" => Input::get('thread_id')
            ,"last_message_id" => Input::get('last_message_id')],
            ["user_id" => 'required', "thread_id" => 'required',"last_message_id" => 'required']);

        $response['is_online'] = false;

        if ($v->passes()) {
            $response['in_conversation'] = 1;

            if(sizeof(OnlineUsers::where('thread_id',Input::get('thread_id'))->get())>0){
                $response['close_conversation'] = 0;
            }else{
                $response['close_conversation'] = 1;
            }

            $thread_geo_info = ThreadGeoInfo::where('thread_id',Input::get('thread_id'))->first();

            if(!empty($thread_geo_info)){
                $response['current_page'] = $thread_geo_info->current_page;
                $all_pages = json_decode($thread_geo_info->all_pages);
                $response['all_pages'] = $all_pages->pages;
            }else{
                $response['current_page'] = "";
                $response['all_pages'] = [];
            }

            $response['messages'] = MessageThread::getServerMessages(Input::get('thread_id'),Input::get('last_message_id'));
        }

        return json_encode($response);

    }

    public function sendMessage()
    {
        if(Input::has("thread_id")&&Input::has("user_id")&&Input::has("message")&&Input::get("user_id")>0&&Input::get("thread_id")>0){
            $thread_message = new ThreadMessages();
            $thread_message->thread_id = Input::get('thread_id');
            $thread_message->sender_id = Input::get('user_id');
            $thread_message->message = Input::get('message');
            $thread_message->save();
            return json_encode(["result"=>1]);
        }else{
            return json_encode(["result"=>0]);
        }
    }

    public function closedConversations()
    {

        $closed_conversations = ClosedConversations::all();

        foreach ($closed_conversations as $closed_conversation) {
            $closed_conversation->user = User::find($closed_conversation->user_id);
            $closed_conversation->operator = User::find($closed_conversation->operator_id);
        }

        $this->data['closed_conversations'] = $closed_conversations;

        return View::make('conversations.closed', $this->data);
    }

    public function transfer($onlineusers_id)
    {
        $online_users = OnlineUsers::find($onlineusers_id);
        $companies = Company::all();

        $this->data['operators'] = [];

        if (sizeof($companies) > 0) {
            $departments = Department::where('company_id', $online_users->company_id)->get();
            $department_ids = Department::where('company_id', $online_users->company_id)->lists('id');

            if(sizeof($department_ids)>0){
                $operator_ids = OperatorsDepartment::whereIn('department_id', $department_ids)->lists('user_id');

                if(sizeof($operator_ids)>0){
                    $this->data['operators'] = User::whereIn('id', $operator_ids)->get();
                }

            }

        } else {
            $departments = [];
        }

        $this->data['companies'] = $companies;
        $this->data['departments'] = $departments;
        $this->data['online_users'] = $online_users;
        $this->data['company_id'] = $online_users->company_id;
        $this->data['department_id'] = $online_users->department_id;
        $this->data['customer'] = User::find($online_users->user_id);

        return View::make('conversations.transfer', $this->data);
    }

    public function storeTransfer(){

        if(Input::has('conversation_id')){

            $online_users = OnlineUsers::find(Input::get('conversation_id'));

            ThreadMessages::where('thread_id',$online_users->thread_id)->where('sender_id',$online_users->operator_id)->update(['sender_id'=>Input::get('operator')]);

            $online_users->company_id = Input::get('company');
            $online_users->department_id = Input::get('department');
            $online_users->operator_id = Input::get('operator');

            $online_users->save();

            Session::flash('success_msg','Conversation transferred successfully');
            return Redirect::to('/conversations/all');


        }else{
            Session::flash('error_msg','Cannot transfer ticket');
            return Redirect::back();
        }

    }


    public function all()
    {

        $online_users = OnlineUsers::all();

        foreach ($online_users as $user) {
            $user->user = User::find($user->user_id);

            if ($user->operator_id > 0)
                $user->operator = User::find($user->operator_id);
        }

        if(\KodeInfo\Utilities\Utils::isDepartmentAdmin(Auth::user()->id)){

            $department_admin = DepartmentAdmins::where('user_id',Auth::user()->id)->first();
            $this->data['department'] = Department::where('id',$department_admin->department_id)->first();
            $this->data["company"] = Company::where('id',$this->data['department']->company_id)->first();

        }elseif (\KodeInfo\Utilities\Utils::isOperator(Auth::user()->id)) {

            $department_operator = OperatorsDepartment::where('user_id',Auth::user()->id)->first();
            $this->data['department'] = Department::where('id',$department_operator->department_id)->first();
            $this->data["company"] = Company::where('id',$this->data['department']->company_id)->first();

        }


        $this->data['online_users'] = $online_users;

        return View::make('conversations.all', $this->data);
    }

    public function closeConversation($thread_id){

        $online_user = OnlineUsers::where('thread_id',$thread_id)->first();

        $closed_conversation = new ClosedConversations();
        $closed_conversation->user_id = $online_user->user_id;
        $closed_conversation->thread_id = $online_user->thread_id;
        $closed_conversation->operator_id = $online_user->operator_id>0?$online_user->operator_id:Auth::user()->id;
        $closed_conversation->company_id = $online_user->company_id;
        $closed_conversation->department_id = $online_user->department_id;
        $closed_conversation->requested_on = $online_user->requested_on;
        $closed_conversation->started_on = $online_user->started_on>0?$online_user->started_on:\Carbon\Carbon::now();
        $closed_conversation->token = $online_user->token;
        $closed_conversation->ended_on = \Carbon\Carbon::now();
        $closed_conversation->save();

        OnlineUsers::where('thread_id',$thread_id)->delete();

        Session::flash('success_msg','Conversation closed successfully');

        return Redirect::to('/conversations/closed');
    }

    public function accept($thread_id)
    {

        if (Utils::isOperator(Auth::user()->id))
            $canned_messages = CannedMessages::where('operator_id', Auth::user()->id);
        else
            $canned_messages = CannedMessages::all();

        $this->data['canned_messages'] = $canned_messages;

        $online_users = OnlineUsers::where('thread_id', $thread_id)->first();

        if(empty($online_users)){
            Session::flash('error_msg','Another operator is in chat or conversation has been closed');
            return Redirect::to('/conversations/all');
        }

        if($online_users->operator_id>0&&$online_users->operator_id!=Auth::user()->id){
            Session::flash('error_msg','Another operator is already in chat . Ask the admin/operator to transfer the chat');
            return Redirect::to('/conversations/all');
        }

        if($online_users->operator_id<=0){

            $online_users->operator_id = Auth::user()->id;
            $online_users->started_on = \Carbon\Carbon::now();
            $online_users->locked_by_operator = 1;
            $online_users->save();

            $thread = MessageThread::find($thread_id);
            $thread->operator_id = Auth::user()->id;
            $thread->save();

        }else{
            $thread = MessageThread::find($thread_id);
        }

        $messages = MessageThread::getServerMessages($thread_id,0);

        $geo_info = ThreadGeoInfo::where('thread_id',$thread_id)->first();
        $this->data['geo'] = $geo_info;
        $this->data['geo_pages'] = json_decode($geo_info->all_pages);
        $this->data['message_str'] = $messages["messages_str"];
        $this->data['last_message_id'] = $messages["last_message_id"];
        $this->data['thread'] = $thread;

        return View::make('conversations.messages', $this->data);
    }

    public function read($thread_id)
    {

        if (Utils::isOperator(Auth::user()->id))
            $canned_messages = CannedMessages::where('operator_id', Auth::user()->id);
        else
            $canned_messages = CannedMessages::all();

        $this->data['canned_messages'] = $canned_messages;

        $thread = MessageThread::where('id',$thread_id)->first();

        $messages = MessageThread::getServerMessages($thread_id,0);

        $geo_info = ThreadGeoInfo::where('thread_id',$thread_id)->first();
        $this->data['geo'] = $geo_info;
        $this->data['geo_pages'] = json_decode($geo_info->all_pages);

        $this->data['message_str'] = $messages["messages_str"];
        $this->data['last_message_id'] = $messages["last_message_id"];
        $this->data['thread'] = $thread;
        $this->data['closed_conversation'] = 1;

        return View::make('conversations.messages', $this->data);
    }

    public function deleteConversation($thread_id){
        ThreadMessages::where('thread_id',$thread_id)->delete();
        MessageThread::where('id',$thread_id)->delete();
        ClosedConversations::where('thread_id',$thread_id)->delete();
        Session::flash('success_msg','Conversation deleted successfully');
        return Redirect::to('/conversations/closed');
    }
}

