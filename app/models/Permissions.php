<?php

/**
 * Created by PhpStorm.
 * 
 * User: Imran
 * Date: 9/19/14
 * Time: 4:08 PM
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @method static \Illuminate\Database\Query\Builder|\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereEmail($value)
 * @property string $key
 * @property string $text
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Permissions whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\Permissions whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\Permissions whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Permissions whereUpdatedAt($value)
 */
class Permissions extends Eloquent
{

    protected $table = "permissions";

    public $timestamps = true;

    static function hasPermission($permission)
    {

        $users_group = DB::table('users_groups')->where("user_id", Auth::user()->id)->first();

        $group = Groups::find($users_group->group_id);

        if ($group->name == "admin") {
            return true;
        }

        if ($group->name == "department-admin") {

            $company_department_admin = DepartmentAdmins::where("user_id", Auth::user()->id)->first();

            if(empty($company_department_admin)){
                //Not connected to any department so permissions available
                return false;
            }

            $department = Department::find($company_department_admin->department_id);

            $permissions = explode(",", $department->permissions);

            if (in_array($permission, $permissions)) {
                return true;
            } else {
                return false;
            }
        }

    }

} 