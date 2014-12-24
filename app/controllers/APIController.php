<?php

class APIController extends BaseController {

    public function getDepartmentOperators($department_id){
        return Response::json(API::getDepartmentOperators($department_id));
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

    public function startupData(){

        if(Input::get('company_id',0)>0&&Input::get('department_id',0)>0){
            $online_users = OnlineUsers::where('company_id',Input::get('company_id'))->where('department_id',Input::get('department_id'))->get();
        }else{
            $online_users = OnlineUsers::all();
        }

        foreach($online_users as $user){
            $user->user = User::find($user->user_id);

            if($user->operator_id>0)
                $user->operator = User::find($user->operator_id);
        }


        $online_users_stub = View::make('conversations.stub-online-users',['online_users'=>$online_users])->render();

        return json_encode(['online_users'=>$online_users_stub]);
    }

    public function ticketsRefresh(){

        $tickets = Tickets::orderBy('priority','desc')->get();

        foreach($tickets as $ticket){
            $ticket->customer = User::where('id',$ticket->customer_id)->first();
            $ticket->company = Company::where('id',$ticket->company_id)->first();
            $ticket->department = Department::where('id',$ticket->department_id)->first();

            if($ticket->operator_id > 0){
                $ticket->operator = User::where('id',$ticket->operator_id)->first();
            }
        }

        $tickets_all = View::make("tickets.stub-all-tickets",['tickets'=>$tickets])->render();

        return json_encode(['tickets_all'=>$tickets_all]);
    }

}