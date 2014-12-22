<?php

namespace KodeInfo\Forms\Rules;

use KodeInfo\Forms\FormValidator;

class OperatorAddValidator extends FormValidator {

    protected $formData=[];

    /**
     * @var array
     */
    //This fields are required can be overriden using addRule;
    protected $rules=[
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed',
        'company' => 'required',
        'department' => 'required',
        'permissions' => 'required'
    ];

}