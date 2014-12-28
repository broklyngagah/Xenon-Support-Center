<?php

namespace KodeInfo\Mailers;

use View;
use Auth;
use User;
use CompanyCustomers;
use Company;

class UsersMailer extends Mailers {

    public $mailchimp;

    function __construct(){
        $this->mailchimp= new \KodeInfo\Templates\Mailchimp\TemplatesList();
    }

    public function welcome($recipient_email,$recipient_name,$data,$use_mailchimp=false,$send_mail=true){

        $subject = "Thanks for register . Here are your credentials";

        if($use_mailchimp){
            $view = 'emails.users.mailchimp_view';
        }else{

            $settings =  json_decode(\Settings::where('key','mailchimp')->pluck('value'));

            if($settings->use_mailchimp){

                $paired = \PairedTemplates::where('view','emails.users.welcome')->first();

                if(!empty($paired)){

                    $template = $this->mailchimp->getTemplate($paired->template_id);

                    $file_path = app_path()."/views/emails/users/mailchimp_view.blade.php";

                    \File::put($file_path,$template['preview']);

                    $view = 'emails.users.mailchimp_view';

                }else{
                    $view = 'emails.users.welcome';
                }

            }else{
                $view = 'emails.users.welcome';
            }

        }

        if($send_mail)
            $this->sendTo($recipient_email , $recipient_name,$subject,$view,$data);
        else
            return $data;
    }

    public function activate($recipient_email,$recipient_name,$data,$use_mailchimp=false,$send_mail=true){

        $subject = "Thanks for register . Please activate your account";

        if($use_mailchimp){
            $view = 'emails.users.mailchimp_view';
        }else{

            $settings =  json_decode(\Settings::where('key','mailchimp')->pluck('value'));

            if($settings->use_mailchimp){

                $paired = \PairedTemplates::where('view','emails.users.activate')->first();

                if(!empty($paired)){

                    $template = $this->mailchimp->getTemplate($paired->template_id);

                    $file_path = app_path()."/views/emails/users/mailchimp_view.blade.php";

                    \File::put($file_path,$template['preview']);

                    $view = 'emails.users.mailchimp_view';

                }else{
                    $view = 'emails.users.activate';
                }

            }else{
                $view = 'emails.users.activate';
            }
        }

        if($send_mail)
            $this->sendTo($recipient_email,$recipient_name,$subject,$view,$data);
        else
            return $data;

    }

    public function reset_password($recipient_email,$recipient_name,$data,$use_mailchimp=false,$send_mail=true){

        $subject = "You can requested password reset . Click on the link to reset password";

        if($use_mailchimp){
            $view = 'emails.users.mailchimp_view';
        }else{

            $settings =  json_decode(\Settings::where('key','mailchimp')->pluck('value'));

            if($settings->use_mailchimp){

                $paired = \PairedTemplates::where('view','emails.users.reset_password')->first();

                if(!empty($paired)){

                    $template = $this->mailchimp->getTemplate($paired->template_id);

                    $file_path = app_path()."/views/emails/users/mailchimp_view.blade.php";

                    \File::put($file_path,$template['preview']);

                    $view = 'emails.users.mailchimp_view';

                }else{
                    $view = 'emails.users.reset_password';
                }

            }else{
                $view = 'emails.users.reset_password';
            }
        }

        if($send_mail)
            $this->sendTo($recipient_email,$recipient_name,$subject,$view,$data);
        else
            return $data;
    }

    public function password_changed($recipient_email,$recipient_name,$data,$use_mailchimp=false,$send_mail=true){
        $subject = "Password changed . If you have not requested then please contact admin";

        //$data['name'] = $user->name;

        if($use_mailchimp){
            $view = 'emails.users.mailchimp_view';
        }else{
            $settings =  json_decode(\Settings::where('key','mailchimp')->pluck('value'));

            if($settings->use_mailchimp){

                $paired = \PairedTemplates::where('view','emails.users.password_changed')->first();

                if(!empty($paired)){

                    $template = $this->mailchimp->getTemplate($paired->template_id);

                    $file_path = app_path()."/views/emails/users/mailchimp_view.blade.php";

                    \File::put($file_path,$template['preview']);

                    $view = 'emails.users.mailchimp_view';

                }else{
                    $view = 'emails.users.password_changed';
                }

            }else{
                $view = 'emails.users.password_changed';
            }
        }

        if($send_mail)
            $this->sendTo($recipient_email,$recipient_name,$subject,$view,$data);
        else
            return $data;
    }

}