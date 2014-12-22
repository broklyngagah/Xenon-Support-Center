<?php

namespace KodeInfo\Mailers;

use View;
use User;
use Auth;
use Tickets;

class TicketsMailer extends Mailers {

    public function created($recipient_email,$recipient_name,$data,$use_mailchimp=false,$send_mail=true){

        $data['ticket']->status_txt = Tickets::resolveStatus($data['ticket']->id);

        $subject = "Ticket #".$data['ticket']->id." , Status - ".$data['ticket']->status_txt." , Thanks for creating ticket . We will respond as soon as possible";

        $data['customer'] = User::find($data['ticket']->customer_id);

        if($use_mailchimp){
            $view = 'emails.tickets.mailchimp_view';
        }else{
            $view = 'emails.tickets.ticket_created';
        }

        if($send_mail)
            $this->sendTo($recipient_email,$recipient_name,$subject,$view,$data);
        else
            return $data;
    }

    public function updated($recipient_email,$recipient_name,$data,$use_mailchimp=false,$send_mail=true){

        $data['ticket']->status_txt = Tickets::resolveStatus($data['ticket']->id);

        $subject = "Ticket #".$data['ticket']->id." , Status - ".$data['ticket']->status_txt." , Your ticket have been updated . Please login to dashboard to view";

        $data['customer'] = User::find($data['ticket']->customer_id);
        $data['operator'] = User::find($data['ticket']->operator_id);

        if($use_mailchimp){
            $view = 'emails.tickets.mailchimp_view';
        }else{
            $view = 'emails.tickets.ticket_updated';
        }

        if($send_mail)
            $this->sendTo($recipient_email,$recipient_name,$subject,$view,$data);
        else
            return $data;

    }
}