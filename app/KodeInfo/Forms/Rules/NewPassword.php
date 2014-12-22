<?php

namespace KodeInfo\Forms\Rules;

use KodeInfo\Forms\FormValidator;

class NewPassword extends FormValidator {

    protected $formData=[];

    /**
     * @var array
     */
    //This fields are required can be overriden using addRule;
    protected $rules=[
        'password' => 'required|min:4|max:32|confirmed',
        'password_confirmation' => 'required|min:4|max:32'
    ];

}