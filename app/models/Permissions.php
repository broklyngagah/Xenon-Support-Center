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

    static function hasAnyConversionsPermissions(){
        if(Permissions::hasPermission('conversations.accept')||Permissions::hasPermission('conversations.close')
            ||Permissions::hasPermission('conversations.delete')||Permissions::hasPermission('conversations.closed')){
            return true;
        }else{
            return false;
        }
    }

    static function hasAnyCannedPermissions(){
        if(Permissions::hasPermission('canned_messages.create')||Permissions::hasPermission('canned_messages.edit')
            ||Permissions::hasPermission('canned_messages.all')||Permissions::hasPermission('canned_messages.delete')){
            return true;
        }else{
            return false;
        }
    }

    static function hasAnyOperatorsPermissions(){
        if(Permissions::hasPermission('operators.create')||Permissions::hasPermission('operators.edit')
            ||Permissions::hasPermission('operators.activate')||Permissions::hasPermission('operators.delete')
            ||Permissions::hasPermission('operators.all')){
            return true;
        }else{
            return false;
        }
    }

    static function hasAnyDepartmentsPermissions(){
        if(Permissions::hasPermission('departments.create')||Permissions::hasPermission('departments.edit')
            ||Permissions::hasPermission('departments.all')||Permissions::hasPermission('departments.delete')){
            return true;
        }else{
            return false;
        }
    }

    static function hasAnyDepartmentAdminsPermissions(){
        if(Permissions::hasPermission('departments_admins.create')||Permissions::hasPermission('departments_admins.edit')
            ||Permissions::hasPermission('departments_admins.remove')||Permissions::hasPermission('departments_admins.activate')
            ||Permissions::hasPermission('departments_admins.all')||Permissions::hasPermission('departments_admins.delete')){
            return true;
        }else{
            return false;
        }
    }

    static function hasAnyMailchimpPermissions(){

        $settings =  json_decode(\Settings::where('key','mailchimp')->pluck('value'));

        if((Permissions::hasPermission('mailchimp.pair_email')||Permissions::hasPermission('mailchimp.all')
            ||Permissions::hasPermission('mailchimp.delete'))&&$settings->use_mailchimp){
            return true;
        }else{
            return false;
        }
    }

    static function hasAnyTicketsPermissions(){
        if(Permissions::hasPermission('tickets.create')||Permissions::hasPermission('tickets.edit')
            ||Permissions::hasPermission('tickets.all')||Permissions::hasPermission('tickets.delete')){
            return true;
        }else{
            return false;
        }
    }

    static function hasAnyCustomersPermissions(){
        if(Permissions::hasPermission('customers.create')||Permissions::hasPermission('customers.edit')
            ||Permissions::hasPermission('customers.all')||Permissions::hasPermission('customers.delete')){
            return true;
        }else{
            return false;
        }
    }

    static function hasAnyCompaniesPermissions(){
        if(Permissions::hasPermission('companies.create')||Permissions::hasPermission('companies.edit')
            ||Permissions::hasPermission('companies.all')||Permissions::hasPermission('companies.delete')){
            return true;
        }else{
            return false;
        }
    }

    static function hasAnyBlockingPermissions(){
        if(Permissions::hasPermission('blocking.block')||Permissions::hasPermission('blocking.all')
            ||Permissions::hasPermission('blocking.delete')){
            return true;
        }else{
            return false;
        }
    }


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
                Session::flash("error_msg",trans('msgs.you_are_not_connected_to_any_department'));
                //Not connected to any department so no permissions available
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

        if ($group->name == "operator") {

            $operator = User::find(Auth::user()->id);

            $permissions = explode(",", $operator->permissions);

            if (in_array($permission, $permissions)) {
                return true;
            } else {
                return false;
            }
        }

    }

} 