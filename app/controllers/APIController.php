<?php

class APIController extends BaseController {

    public function getDepartmentOperators($department_id){
        return Response::json(API::getDepartmentOperators($department_id));
    }

    public function getDepartmentOperatorsWithAdmin($department_id){
        return Response::json(API::getDepartmentOperatorsWithAdmin($department_id));
    }

    public function getCode($company_id){

        $company = Company::where('id',$company_id)->first();

        $arr = [];

        $arr['code'] = '&lt;script src=&quot;http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js&quot;&gt;&lt;/script&gt;<br/>	&lt;link href=&quot;'.URL::to('/').'/assets/xenon_chat/style.css&quot; rel=&quot;stylesheet&quot; type=&quot;text/css&quot;&gt;<br/>	&lt;script src=&quot;'.URL::to('/').'/assets/xenon_chat/script.js&quot; type=&quot;text/javascript&quot;&gt;&lt;/script&gt;<br/><br/>	&lt;script type=&quot;text/javascript&quot;&gt;<br/>	$(document).ready(function () {<br/>		$(&quot;#xenon-chat-widget&quot;).XENON_Initialize({company: '.$company_id.', domain: &quot;'.URL::to('/').'&quot;});<br/>	});<br/>	&lt;/script&gt;&lt;div id=&quot;xenon-chat-widget&quot;&gt;&lt;/div&gt;';

        $arr['domain'] = $company->domain;

        return json_encode($arr);
    }

    public function changeStatus($status){
        $user = User::find(Auth::user()->id);
        $user->is_online = $status;
        $user->save();
        return Redirect::back();
    }

    public function getCompanyFreeDepartmentAdmins($company_id){
        return Response::json(API::getCompanyFreeDepartmentAdmins($company_id));
    }

    public function getCompanyDepartments($company_id){
        return Response::json(API::getCompanyDepartments($company_id));
    }

    public function getDepartmentPermissions($department_id){
        return Response::json(API::getDepartmentPermissions($department_id));
    }

    public function logIP(){
        if(Input::has('ip_address')){
            Session::put('client_ip',Input::get('ip_address'));
        }
    }

    public function conversationsRefresh(){

        if(Input::get('company_id',0)>0&&Input::get('department_id',0)>0){
            $online_users = OnlineUsers::where('company_id',Input::get('company_id'))->where('department_id',Input::get('department_id'))->orderBy('id','desc')->get();
        }else{
            $online_users = OnlineUsers::orderBy('id','desc')->get();
        }

        $conversations_arr = [];

        foreach($online_users as $online){

            if(sizeof(User::where('id',$online->user_id)->get())>0) {
                $online->user = User::find($online->user_id);

                if ($online->operator_id > 0)
                    $online->operator = User::find($online->operator_id);

                $single_conversation = [];
                $single_conversation[] = $online->id;
                $single_conversation[] = $online->user->name;
                $single_conversation[] = $online->user->email;
                $single_conversation[] = isset($online->operator) ? $online->operator->name : "<label class='label label-warning'>" . trans('msgs.none') . "</label>";
                $single_conversation[] = \KodeInfo\Utilities\Utils::prettyDate($online->requested_on, true);
                $single_conversation[] = \KodeInfo\Utilities\Utils::prettyDate($online->started_on, true);
                $single_conversation[] = $online->locked_by_operator == 1 ? "<label class='label label-warning'>" . trans('msgs.yes') . "</label>" : "<label class='label label-primary'>" . trans('msgs.no') . "</label>";

                if (!isset($online->operator))
                    $single_conversation[] = '<td><a href="/conversations/accept/' . $online->thread_id . '" class="btn btn-success btn-sm"> <i class="icon-checkmark4"></i> ' . trans('msgs.accept') . ' </a></td>';

                if (isset($online->operator) && $online->operator->id == Auth::user()->id)
                    $single_conversation[] = '<td><a href="/conversations/accept/' . $online->thread_id . '" class="btn btn-success btn-sm"> <i class="icon-checkmark4"></i> ' . trans('msgs.reply') . ' </a></td>';

                if (isset($online->operator) && $online->operator->id != Auth::user()->id)
                    $single_conversation[] = '<td><a disabled class="btn btn-success btn-sm"> <i class="icon-lock3"></i> ' . trans('msgs.accept') . ' </a></td>';

                $single_conversation[] = '<td><a href="/conversations/transfer/' . $online->id . '" class="btn btn-warning btn-sm"> <i class="icon-share3"></i> ' . trans('msgs.transfer') . ' </a></td>';

                $single_conversation[] = '<td><a href="/conversations/close/' . $online->thread_id . '" class="btn btn-danger btn-sm"> <i class="icon-lock3"></i> ' . trans('msgs.close') . ' </a></td>';

                $conversations_arr[] = $single_conversation;
            }
        }

        return json_encode(['aaData'=>$conversations_arr]);
    }

    public function masterRefresh(){

        if(Input::get('company_id',0)>0&&Input::get('department_id',0)>0){
            $online_users = OnlineUsers::where('company_id',Input::get('company_id'))->where('department_id',Input::get('department_id'))->orderBy('id','desc')->get();
        }else{
            $online_users = OnlineUsers::orderBy('id','desc')->get();
        }

        $conversations_arr = [];

        foreach($online_users as $online){

            if(sizeof(User::where('id',$online->user_id)->get())>0) {
                $online->user = User::find($online->user_id);

                if ($online->operator_id > 0)
                    $online->operator = User::find($online->operator_id);

                $single_conversation = [];
                $single_conversation[] = $online->user->name;
                $single_conversation[] = $online->user->email;

                if (!isset($online->operator)) {
                    $single_conversation[] = '<td><a href="/conversations/accept/' . $online->thread_id . '" class="btn btn-success btn-sm"> <i class="icon-checkmark4"></i> ' . trans('msgs.accept') . ' </a></td>';
                    $conversations_arr[] = $single_conversation;
                }

                if (isset($online->operator) && $online->operator->id == Auth::user()->id) {
                    $single_conversation[] = '<td><a href="/conversations/accept/' . $online->thread_id . '" class="btn btn-success btn-sm"> <i class="icon-checkmark4"></i> ' . trans('msgs.reply') . ' </a></td>';
                    $conversations_arr[] = $single_conversation;
                }
            }
        }

        return json_encode(['aaData'=>$conversations_arr]);
    }

    public function ticketsRefresh(){

        if(Input::get('company_id',0)>0&&Input::get('department_id',0)>0){
            $tickets = Tickets::orderBy('priority','desc')->where('company_id',Input::get('company_id'))->where('department_id',Input::get('department_id'))->get();
        }else{
            $tickets = Tickets::orderBy('priority','desc')->get();
        }

        $tickets_arr = [];

        foreach($tickets as $ticket){
            $ticket->customer = User::where('id',$ticket->customer_id)->first();
            $ticket->company = Company::where('id',$ticket->company_id)->first();
            $ticket->department = Department::where('id',$ticket->department_id)->first();

            if($ticket->operator_id > 0){
                $ticket->operator = User::where('id',$ticket->operator_id)->first();
            }

            $single_ticket = [];
            $single_ticket[] = $ticket->id;
            $single_ticket[] = isset($ticket->company)?$ticket->company->name:trans('msgs.none');
            $single_ticket[] = isset($ticket->department)?$ticket->department->name:trans('msgs.none');
            $single_ticket[] = isset($ticket->customer)?$ticket->customer->name:trans('msgs.none');
            $single_ticket[] = isset($ticket->customer)?$ticket->customer->email:trans('msgs.none');
            $single_ticket[] = $ticket->subject;
            $single_ticket[] = isset($ticket->operator)?$ticket->operator->name:trans('msgs.none');

            if($ticket->priority==Tickets::PRIORITY_LOW)
                $single_ticket[] = '<td ><label class="label label-primary" > '.trans("msgs.low").' </label ></td >';

            if($ticket->priority==Tickets::PRIORITY_MEDIUM)
                $single_ticket[] = '<td><label class="label label-primary">'.trans("msgs.medium").'</label></td>';

            if($ticket->priority==Tickets::PRIORITY_HIGH)
                $single_ticket[] = '<td><label class="label label-warning">'.trans("msgs.high").'</label></td>';

            if($ticket->priority==Tickets::PRIORITY_URGENT)
                $single_ticket[] = '<td><label class="label label-danger">'.trans("msgs.urgent").'</label></td>';

            if($ticket->status==Tickets::TICKET_NEW)
                $single_ticket[] = '<td><label class="label label-warning">'.trans("msgs.new").'</label></td>';

            if($ticket->status==Tickets::TICKET_PENDING)
                $single_ticket[] = '<td><label class="label label-primary">'.trans("msgs.pending").'</label></td>';

            if($ticket->status==Tickets::TICKET_RESOLVED)
                $single_ticket[] = '<td><label class="label label-success">'.trans("msgs.resolved").'</label></td>';

            if(!isset($ticket->operator))
                $single_ticket[] = '<td><a href="/tickets/read/'.$ticket->thread_id.'" class="btn btn-success btn-sm"> <i class="icon-checkmark4"></i> '.trans("msgs.accept").' </a></td>';

            if(isset($ticket->operator)&&$ticket->operator->id==Auth::user()->id)
                $single_ticket[] = '<td><a href="/tickets/read/'.$ticket->thread_id.'" class="btn btn-success btn-sm"> <i class="icon-checkmark4"></i> '.trans("msgs.reply").' </a></td>';

            if(isset($ticket->operator)&&$ticket->operator->id!=Auth::user()->id)
                $single_ticket[] = '<td><a disabled class="btn btn-success btn-sm"> <i class="icon-lock3"></i> '.trans("msgs.accept").' </a></td>';

            $single_ticket[] = '<td><a href="/tickets/transfer/'.$ticket->id.'" class="btn btn-warning btn-sm"> <i class="icon-share3"></i> '.trans("msgs.transfer").' </a></td>';
            $single_ticket[] = '<td><a href="/tickets/delete/'.$ticket->thread_id.'" class="btn btn-danger btn-sm"> <i class="icon-remove3"></i> '.trans("msgs.delete").' </a></td>';

            $tickets_arr[] = $single_ticket;

        }

        return json_encode(['aaData'=>$tickets_arr]);
    }

}