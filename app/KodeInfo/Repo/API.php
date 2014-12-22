<?php

class API {

    public static function getDepartmentOperators($department_id){
        $operator_ids = OperatorsDepartment::where('department_id',$department_id)->lists('user_id');

        if(sizeof($operator_ids)>0){
            return User::whereIn('id',$operator_ids)->get();
        }else{
            return [];
        }
    }

    public static function getCompanyDepartments($company_id){
        $departments = Department::where("company_id",$company_id)->get();
        return $departments;
    }

    public static function getCompanyFreeDepartmentAdmins($company_id){
        return DepartmentAdmins::getFreeDepartmentAdmins($company_id);
    }

    public static function getDepartmentPermissions($department_id){
        $department = Department::find($department_id);
        $permissions_keys = explode(",", $department->permissions);
        $permissions = Permissions::whereIn('key', $permissions_keys)->get();
        return $permissions;
    }

}