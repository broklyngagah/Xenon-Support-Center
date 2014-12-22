<?php

/**
 * DepartmentAdmins
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $department_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\DepartmentAdmins whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\DepartmentAdmins whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\DepartmentAdmins whereDepartmentId($value)
 * @method static \Illuminate\Database\Query\Builder|\DepartmentAdmins whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\DepartmentAdmins whereUpdatedAt($value)
 */
class DepartmentAdmins extends Eloquent {

    protected $table="department_admins";

    static function getFreeDepartmentAdmins($company_id,array $exclude_admin=[]){

        $user_ids = CompanyDepartmentAdmins::where("company_id",$company_id)->lists("user_id");

        $department_admins_ids = [];

        if(sizeof($user_ids)>0)
            $department_admins_ids = DepartmentAdmins::whereIn('user_id',$user_ids)->lists('user_id');

        $department_admins = [];

        if(sizeof($user_ids)>0){
            $admins = User::whereIn("id",$user_ids)->get();

            foreach($admins as $admin) {
                if (!in_array($admin->id,$exclude_admin)){
                    if (!in_array($admin->id, $department_admins_ids)) {
                        array_push($department_admins, $admin);
                    }
                }else{
                    array_push($department_admins, $admin);
                }
            }

        }

        return $department_admins;
    }

} 