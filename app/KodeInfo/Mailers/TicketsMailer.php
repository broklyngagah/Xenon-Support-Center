<?php

namespace KodeInfo\Mailers;

use View;
use User;
use Auth;
use Tickets;

class TicketsMailer extends Mailers {

    public $mailchimp;

    function __construct(){
        $this->mailchimp= new \KodeInfo\Templates\Mailchimp\TemplatesList();
     }

    public function created($recipient_email,$recipient_name,$data,$use_mailchimp=false,$send_mail=true){

        $subject = "Ticket #".$data['ticket_id']." , Status - ".$data['ticket_status_txt']." , Thanks for creating ticket . We will respond as soon as possible";

        if($use_mailchimp){
            $view = 'emails.tickets.mailchimp_view';
        }else{

            $settings =  json_decode(\Settings::where('key','mailchimp')->pluck('value'));

            if($settings->use_mailchimp){

                $paired = \PairedTemplates::where('view','emails.tickets.ticket_created')->first();

                if(!empty($paired)){

                    $template = $this->mailchimp->getTemplate($paired->template_id);

                    $file_path = app_path()."/views/emails/users/mailchimp_view.blade.php";

                    \File::put($file_path,$template['preview']);

                    $view = 'emails.users.mailchimp_view';

                }else{
                    $view = 'emails.tickets.ticket_created';
                }

            }else{
                $view = 'emails.tickets.ticket_created';
            }
        }

        if($send_mail)
            $this->sendTo($recipient_email,$recipient_name,$subject,$view,$data);
        else
            return $data;
    }

    public function updated($recipient_email,$recipient_name,$data,$use_mailchimp=false,$send_mail=true){

        $subject = "Ticket #".$data['ticket_id']." , Status - ".$data['ticket_status_txt']." , Your ticket have been updated . Please login to dashboard to view";

        if($use_mailchimp){
            $view = 'emails.tickets.mailchimp_view';
        }else{

            $settings =  json_decode(\Settings::where('key','mailchimp')->pluck('value'));

            if($settings->use_mailchimp){

                $paired = \PairedTemplates::where('view','emails.tickets.ticket_updated')->first();

                if(!empty($paired)){

                    $template = $this->mailchimp->getTemplate($paired->template_id);

                    $file_path = app_path()."/views/emails/users/mailchimp_view.blade.php";

                    \File::put($file_path,$template['preview']);

                    $view = 'emails.users.mailchimp_view';

                }else{
                    $view = 'emails.tickets.ticket_updated';
                }

            }else{
                $view = 'emails.tickets.ticket_updated';
            }
        }

        if($send_mail)
            $this->sendTo($recipient_email,$recipient_name,$subject,$view,$data);
        else
            return $data;

    }
}