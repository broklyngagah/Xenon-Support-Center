<?php
        return [
	          'driver' => 'smtp',
	          'host' => 'smtp.gmail.com',
	          'port' => 587,
	          'from' => ['address' => 'shellprog@gmail.com', 'name' => 'Support Center'],
	          'reply-to' => ['address' => 'shellprog@gmail.com','name' => 'Support Center'],
	          'encryption' => 'tls',
	          'username' => 'shellprog@gmail.com',
	          'password' => 'unity@#1100',
	          'sendmail' => '/usr/sbin/sendmail -bs',
	          'pretend' => false,
	    ];