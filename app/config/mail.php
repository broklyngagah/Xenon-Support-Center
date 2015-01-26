<?php
        return [
	          'driver' => 'mailgun',
	          'host' => '',
	          'port' => 587,
	          'from' => ['address' => 'shellprog@gmail.com', 'name' => 'Support Center'],
	          'reply-to' => ['address' => 'shellprog@gmail.com','name' => 'shellprog@gmail.com'],
	          'encryption' => 'tls',
	          'username' => '',
	          'password' => '',
	          'sendmail' => '/usr/sbin/sendmail -bs',
	          'pretend' => false,
	          'use_mailgun' => true,
	    ];