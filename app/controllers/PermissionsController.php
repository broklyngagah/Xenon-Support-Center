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

            Session::flash("success_msg","Permission created successfully");
            return Redirect::to("/permissions/create")->withInput();

        }else{
            Session::flash("error_msg","All fields are required");
            return Redirect::to("/permissions/all");
        }
    }

    public function getPermission($id){
        return Response::json(Permissions::find($id));
    }

    public function update(){

        if(!Input::has("id") || !Input::has("text")){
            Session::flash('error_msg',"All fields are required");
            return Redirect::to('/permissions/all');
        }

        try{
            $permission = \Permissions::findOrFail(Input::get('id'));
            $permission->text = Input::get('text');
            $permission->save();

            Session::flash('success_msg',"Permission updated successfully");
            return Redirect::to('/permissions/all');
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            Session::flash('error_msg',"Permission not found");
            return Redirect::to('/permissions/all');
        }
    }

    public function delete($id){
        try{

            Permissions::findOrFail($id)->delete();
            Session::flash("success_msg","Permission deleted successfully");
            return Redirect::to("/permissions/all");

        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            Session::flash("error_msg","Permission not found");
            return Redirect::to("/permissions/all");
        }
    }
}