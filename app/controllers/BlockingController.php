<?php

class BlockingController extends BaseController {

    public function  __construct()
    {
        $this->beforeFilter('has_permission:blocking.block', array('only' => array('create', 'store')));
        $this->beforeFilter('has_permission:blocking.delete', array('only' => array('delete')));
        $this->beforeFilter('has_permission:blocking.all', array('only' => array('all')));
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

            if(Config::get('site-config.is_demo')){
                Session::flash('error_msg','Demo - Feature is disabled');
                return Redirect::to('/dashboard');
            }

            $block = new Blocking();
            $block->ip_address = Input::get('ip_address');
            $block->should_block_chat = Input::get('should_block_chat');
            $block->should_block_tickets = Input::get('should_block_tickets');
            $block->should_block_login = Input::get('should_block_login');
            $block->should_block_web_access = Input::get('should_block_web_access');
            $block->save();

            RecentActivities::createActivity("IP '".$block->ip_address."' Blocked by ID:'".Auth::user()->id."' Name:'".Auth::user()->name."'");

            Session::flash('success_msg',trans('msgs.ip_blocked_success'));
            return Redirect::back();

        }else{
            Session::flash('error_msg',trans('msgs.ip_address_required'));
            return Redirect::back();
        }

    }

    public function delete($id){
        RecentActivities::createActivity("Blocked IP '".Blocking::where('id',$id)->pluck('ip_address')."' deleted by ID:'".Auth::user()->id."' Name:'".Auth::user()->name."'");
        Blocking::where('id',$id)->delete();
        Session::flash('success_msg',trans('msgs.ip_deleted_success'));
        return Redirect::to('/blocking/all');
    }

}