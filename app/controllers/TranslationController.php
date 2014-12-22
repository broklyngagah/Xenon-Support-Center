<?php

class TranslationController extends BaseController {

    function __construct()
    {

        $this->beforeFilter('has_permission:translations.create', array('only' => array('create','store')));
        $this->beforeFilter('has_permission:translations.edit', array('only' => array('edit','update')));
        $this->beforeFilter('has_permission:translations.view', array('only' => array('all')));
        $this->beforeFilter('has_permission:translations.delete', array('only' => array('delete')));

    }

    public function all(){

        $translations = Translations::all();

        $this->data['translations'] = $translations;

        return View::make('translations.all',$this->data);
    }

    public function view(){

    }

    public function create(){

    }

    public function delete($id){

        $short_codes = Config::get('short_codes');

        if($id==1){
            Session::flash('error_msg','English Language cannot be deleted');
            return Redirect::to('translations/all');
        }

    }

}