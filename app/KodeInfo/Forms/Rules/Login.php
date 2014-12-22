<?php

namespace KodeInfo\Forms\Rules;

use KodeInfo\Forms\FormValidator;

class Login extends FormValidator {

    protected $formData=[];

    /**
     * @var array
     */
    //This fields are required can be overriden using addRule;
    protected $rules=[
        'identity' => 'required|min:4|max:32',
        'password' => 'required|min:6'
    ];

}