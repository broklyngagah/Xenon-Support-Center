<?php

namespace KodeInfo\Mailers;

use Mail;
use Config;
use Settings;
use View;
use Session;
use Redirect;

class Mailers
{

    public function sendTo($recipient_email , $recipient_name , $subject , $view, $data = [])
    {

        if(!Config::get('site-config.is_demo')){
            $client = \App::make("Bogardo\Mailgun\Mailgun");

            if (Config::get('mailgun::use_mailgun')) {
                $client->send($view, $data, function ($message) use ($subject , $recipient_email , $recipient_name) {
                    $message->to($recipient_email, $recipient_email)->subject($subject);
                });
            } else {
                Mail::send($view, $data, function ($message) use ($subject, $recipient_email , $recipient_name) {
                    $message->to($recipient_email, $recipient_email)->subject($subject);
                });
            }
        }

    }

}