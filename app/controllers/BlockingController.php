<?php

class BlockingController extends BaseController {

    public function  __construct()
    {
        $this->beforeFilter('has_permission:blocking.block', array('only' => array('create', 'store')));
        $this->beforeFilter('has_permission:blocking.delete', array('only' => array('delete')));
        $this->beforeFilter('has_permission:blocking.view', array('only' => array('all')));
    }

    public function all(){
        $this->data['blocking'] = Blocking::all();
        return View::make('blocking.all',$this->data);
    }

    public function create(){
        return View::make('blocking.create');
    }

    public function store(){

        if(strlen(Input::get('ip_address'))>0){

            $block = new Blocking();
            $block->ip_address = Input::get('ip_address');
            $block->should_block_chat = Input::get('should_block_chat');
            $block->should_block_tickets = Input::get('should_block_tickets');
            $block->should_block_login = Input::get('should_block_login');
            $block->should_block_web_access = Input::get('should_block_web_access');
            $block->save();

            Session::flash('success_msg','Ip successfully blocked');
            return Redirect::back();

        }else{
            Session::flash('error_msg','Ip Address is required');
            return Redirect::back();
        }

    }

    public function delete($id){
        Blocking::where('id',$id)->delete();
        Session::flash('success_msg','IP deleted successfully');
        return Redirect::to('/blocking/all');
    }

}