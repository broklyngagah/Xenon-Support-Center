<?php

namespace KodeInfo\Forms\Rules;

use KodeInfo\Forms\FormValidator;

class PermissionsUpdateValidator extends FormValidator {

    protected $formData=[];

    /**
     * @var array
     */
    //This fields are required can be overriden using addRule;
    protected $rules=[
        'id' => 'required',
        'name' => 'required'
    ];

}