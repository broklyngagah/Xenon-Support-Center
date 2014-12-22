<?php

namespace KodeInfo\Forms;

use Illuminate\Validation\Factory as Validator;

/**
 * Class FormValidator
 */
abstract class FormValidator
{

    /**
     * @var
     */
    protected $validator;

    /**
     * @var
     */
    protected $errors;

    /**
     * @var
     */
    protected $validation;

    /**
     * @param Validator $validator
     */
    function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }


    /**
     * @param array $formData
     * @return bool
     */
    public function validate(array $formData)
    {
        $this->setData($formData);

        $this->validation = $this->validator->make($this->getData() , $this->getRules());

        if($this->validation->fails()){

            //$this->setErrors($this->validation->errors);

            return false;

        }

        return true;
    }

    public function setData($formData){
        $this->formData = $formData;
    }

    public function getData(){
        return $this->formData;
    }

    /**
     * @param $keys
     * @param $values
     *
     * It adds or modify existing rules array
     */
    public function addRule($keys,$values){

        if(is_array($keys)&&is_array($values)&&count($keys)==count($values)){

            for($var=0;$var<=count($keys);$var++){
                $this->rules[$keys[$var]]=$values[$var];
            }

        }else{
            $this->rules[$keys]=$values;
        }

    }

    /**
     *
     */
    public function clear(){
        unset($this->rules);
        $this->rules=[];
    }

    /**
     * @param $keys
     *
     *  Remove rules from rules array
     */
    public function removeRule($keys){

        if(is_array($keys)){
            for($var=0;$var<=count($keys);$var++){
                unset($this->rules[$keys[$var]]);
            }
        }else{
            unset($this->rules[$keys]);
        }

    }

    /**
     * @return mixed
     */
    public function getRules()
    {
        return $this->rules;
    }

    public function getMessages(){
        return (array)$this->validation->messages()->all();
    }

    public function getValidator(){
        return $this->validator;
    }

    public function getValidation(){
        return $this->validation;
    }


    /**
     * @return mixed
     */
    public function getErrors(){

        return $this->validation->errors;

    }



}