<?php

namespace KodeInfo\Forms\Rules;

use KodeInfo\Forms\FormValidator;

class CustomerAddValidator extends FormValidator {

    protected $formData=[];

    /**
     * @var array
     */
    //This fields are required can be overriden using addRule;
    protected $rules=[
        'email' => 'required|unique:users,email',
        'password' => 'required|confirmed',
        'name' => 'required'
    ];

}