<?php

namespace KodeInfo\Mailers;

use View;
use Auth;
use User;
use CompanyCustomers;
use Company;

class UsersMailer extends Mailers {

    public function welcome($recipient_email,$recipient_name,$data,$use_mailchimp=false,$send_mail=true){

        $subject = "Thanks for register . Here are your credentials";

        $data['name'] = $recipient_name;
        $data['email'] = $recipient_email;

        $recipient = User::where('email',$recipient_email)->first();
        $company_id = CompanyCustomers::where('customer_id',$recipient->id)->first();
        $data['current_company'] = Company::find($company_id);
        $data['current_user'] = Auth::user();

        if($use_mailchimp){
            $view = 'emails.users.mailchimp_view';
        }else{
            $view = 'emails.users.welcome';
        }

        if($send_mail)
            $this->sendTo($recipient_email , $recipient_name,$subject,$view,$data);
        else
            return $data;
    }

    public function activate($recipient_email,$recipient_name,$data,$use_mailchimp=false,$send_mail=true){

        $subject = "Thanks for register . Please activate your account";

        //$data['name'] = $user->name;
        //$data['user_id'] = $user->id;
        //$data['activation_code'] = $user->activation_code;

        if($use_mailchimp){
            $view = 'emails.users.mailchimp_view';
        }else{
            $view = 'emails.users.activate';
        }

        if($send_mail)
            $this->sendTo($recipient_email,$recipient_name,$subject,$view,$data);
        else
            return $data;

    }

    public function reset_password($recipient_email,$recipient_name,$data,$use_mailchimp=false,$send_mail=true){
        $subject = "You can requested password reset . Click on the link to reset password";

        //$data['name'] = $user->name;
        //$data['email'] = $user->email;
        //$data['reset'] = $user->reset_password_code;

        if($use_mailchimp){
            $view = 'emails.users.mailchimp_view';
        }else{
            $view = 'emails.users.reset_password';
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
            $view = 'emails.users.password_changed';
        }

        if($send_mail)
            $this->sendTo($recipient_email,$recipient_name,$subject,$view,$data);
        else
            return $data;
    }

}