<?php

class PermissionsController extends BaseController {


    function all(){
        $this->data['permissions'] = Permissions::all();
        return View::make('permissions.all',$this->data);
    }

    function create(){
        return View::make('permissions.create',$this->data);
    }

    function store(){
        if(Input::has("key")&&Input::has("text")){

            $permission = new Permissions();
            $permission->key = Input::get("key");
            $permission->text = Input::get("text");
            $permission->save();

            Session::flash("success_msg",trans('msgs.permission_created_success'));
            return Redirect::to("/permissions/create")->withInput();

        }else{
            Session::flash("error_msg",trans('msgs.all_fields_required'));
            return Redirect::to("/permissions/all");
        }
    }

    public function getPermission($id){
        return Response::json(Permissions::find($id));
    }

    public function update(){

        if(!Input::has("id") || !Input::has("text")){
            Session::flash('error_msg',trans('msgs.all_fields_required'));
            return Redirect::to('/permissions/all');
        }

        try{
            $permission = \Permissions::findOrFail(Input::get('id'));
            $permission->text = Input::get('text');
            $permission->save();

            Session::flash('success_msg',trans('msgs.permission_updated_success'));
            return Redirect::to('/permissions/all');
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            Session::flash('error_msg',trans('msgs.permission_not_found'));
            return Redirect::to('/permissions/all');
        }
    }

    public function delete($id){

        if(Config::get('site-config.is_demo')){
            Session::flash('error_msg','Demo : Feature is disabled');
            return Redirect::to('/dashboard');
        }

        try{

            Permissions::findOrFail($id)->delete();
            Session::flash("success_msg",trans('msgs.permission_deleted_success'));
            return Redirect::to("/permissions/all");

        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            Session::flash("error_msg",trans('msgs.permission_not_found'));
            return Redirect::to("/permissions/all");
        }
    }
}