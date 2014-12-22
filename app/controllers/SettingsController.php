<?php

use KodeInfo\Forms\Rules\AccountAddValidator;
use KodeInfo\UserManagement\UserManagement;

class SettingsController extends BaseController
{

    function __construct()
    {
        $this->beforeFilter('has_permission:settings.all', array('only' => array('all')));

    }

    public function all()
    {

        $raw_settings = Settings::all();

        $settings = new StdClass();

        foreach ($raw_settings as $raw_setting) {
            $settings->{$raw_setting->key} = json_decode($raw_setting->value);
        }

        $smtp = new StdClass();
        $smtp->from_address = Config::get('mail.from.address')==null?'':Config::get('mail.from.address');
        $smtp->from_name = Config::get('mail.from.name')==null?'':Config::get('mail.from.name');
        $smtp->reply_to_address = Config::get('mail.reply-to.address')==null?'':Config::get('mail.reply-to.address');
        $smtp->reply_to_name = Config::get('mail.reply-to.name')==null?'':Config::get('mail.reply-to.name');
        $smtp->host = Config::get('mail.host')==null?'':Config::get('mail.host');
        $smtp->username = Config::get('mail.username')==null?'':Config::get('mail.username');
        $smtp->password = Config::get('mail.password')==null?'':Config::get('mail.password');

        $mailgun = new StdClass();
        $mailgun->from_address = Config::get('mailgun::from.address');
        $mailgun->from_name = Config::get('mailgun::from.name');
        $mailgun->reply_to = Config::get('mailgun::reply_to');
        $mailgun->api_key = Config::get('mailgun::api_key');
        $mailgun->public_api_key = Config::get('mailgun::public_api_key');
        $mailgun->domain = Config::get('mailgun::domain');
        $mailgun->force_from_address = Config::get('mailgun::force_from_address');
        $mailgun->catch_all = Config::get('mailgun::catch_all');
        $mailgun->testmode = Config::get('mailgun::testmode');
        $mailgun->use_mailgun = Config::get('mailgun::use_mailgun');

        $settings->smtp = $smtp;
        $settings->mailgun = $mailgun;

        $this->data['settings'] = $settings;

        return View::make('settings', $this->data);
    }

    public function setMailGun()
    {

        if (Input::has('use_mailgun')) {
            $mail_content = "<?php
            return [
	            'from' => [
                'address' => '" . Input::get('from_address') . "',
		        'name' => '" . Input::get('from_name') . "'
	        ],
	        'reply_to' => '" . Input::get('reply_to') . "',
	        'api_key' => '" . Input::get('api_key') . "',
	        'public_api_key' => '" . Input::get('public_api_key') . "',
	        'domain' => '" . Input::get('domain') . "',
	        'force_from_address' => false,
	        'catch_all' => '',
	        'testmode' => false,
	        'use_mailgun' => true
            ];";
        } else {
            $mail_content = "<?php
            return [
	            'from' => [
                'address' => '" . Input::get('from_address') . "',
		        'name' => '" . Input::get('from_name') . "'
	        ],
	        'reply_to' => '" . Input::get('reply_to') . "',
	        'api_key' => '" . Input::get('api_key') . "',
	        'public_api_key' => '" . Input::get('public_api_key') . "',
	        'domain' => '" . Input::get('domain') . "',
	        'force_from_address' => false,
	        'catch_all' => '',
	        'testmode' => false,
	        'use_mailgun' => false
            ];";
        }

        \File::put(app_path() . "/config/packages/bogardo/mailgun/config.php", $mail_content);

        Session::flash('success_msg', 'Mailgun settings updated');

        return Redirect::to('/settings/all#tab-mailgun');

    }

    public function setSMTP()
    {

        $mail_content = "<?php
        return [
	          'driver' => 'smtp',
	          'host' => '".Input::get('host')."',
	          'port' => 587,
	          'from' => ['address' => '".Input::get('from_address')."', 'name' => '".Input::get('from_name')."'],
	          'reply-to' => ['address' => '".Input::get('reply_to_address')."','name' => '".Input::get('reply_to_name')."'],
	          'encryption' => 'tls',
	          'username' => '".Input::get('username')."',
	          'password' => '".Input::get('password')."',
	          'sendmail' => '/usr/sbin/sendmail -bs',
	          'pretend' => false,
	    ];";

        \File::put(app_path() . "/config/mail.php", $mail_content);

        Session::flash('success_msg', 'SMTP settings updated');

        return Redirect::to('/settings/all#tab-smtp');

    }

    public function setMailchimp()
    {

        $values = [
            'use_mailchimp' => Input::has('use_mailchimp'),
            'api_key' => Input::get('api_key')
        ];

        Settings::where('key', 'mailchimp')->update(['value' => json_encode($values)]);

        Session::flash('success_msg', 'Mailchimp settings updated');

        return Redirect::to('/settings/all#tab-mailchimp');

    }

    public function setTickets(){

        $values = [
            'should_send_email_ticket_status_change' => Input::has('should_send_email_ticket_status_change'),
            'should_send_email_ticket_reply' => Input::has('should_send_email_ticket_reply'),
            'convert_chat_ticket_no_operators' => Input::has('convert_chat_ticket_no_operators')
        ];

        Settings::where('key', 'tickets')->update(['value' => json_encode($values)]);

        Session::flash('success_msg', 'Tickets settings updated');

        return Redirect::to('/settings/all#tab-tickets');

    }
}