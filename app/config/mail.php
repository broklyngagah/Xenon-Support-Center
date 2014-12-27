<?php
        return [
	          'driver' => 'smtp',
	          'host' => 'smtp.gmail.com',
	          'port' => 587,
	          'from' => ['address' => 'shellprog@gmail.com', 'name' => 'Support Center'],
	          'reply-to' => ['address' => 'shellprog@gmail.com','name' => 'Support Center'],
	          'encryption' => 'tls',
	          'username' => 'phpdummies@gmail.com',
	          'password' => '#include',
	          'sendmail' => '/usr/sbin/sendmail -bs',
	          'pretend' => false,
	    ];