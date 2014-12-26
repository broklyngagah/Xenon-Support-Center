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
 * @property integer $user_id
 * @property string $description
 * @property string $domain
 * @property string $logo
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Company whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Company whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Company whereDomain($value)
 * @method static \Illuminate\Database\Query\Builder|\Company whereLogo($value)
 * @method static \Illuminate\Database\Query\Builder|\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Company whereUpdatedAt($value)
 */

class Company extends Eloquent {

    protected $table="companies";

    static function permissions($permissions){
        $p_keys = json_decode($permissions);

        $p_texts = Permissions::whereIn('key',$p_keys)->lists('text');

        return implode('</br>',$p_texts);
    }

    static function operatorsOnline($company_id){

        $response = 0;

        $department_admin_ids = CompanyDepartmentAdmins::where('company_id', $company_id)->lists('user_id');
        $company = Company::find($company_id);
        $user = User::find($company->user_id);

        if($user->is_online==1){
            return 1;
        }else{
            foreach ($department_admin_ids as $admin_id) {
                if ($response == 0) {
                    $user = User::find($admin_id);

                    if ($user->is_online == 1) {
                        return 1;
                    } else {
                        $department_admin = DepartmentAdmins::where('user_id',$admin_id)->first();

                        if(!empty($department_admin)) {
                            $operators_ids = OperatorsDepartment::where('department_id', $department_admin->department_id)->lists('user_id');

                            foreach ($operators_ids as $operators_id) {
                                $user = User::find($operators_id);
                                if ($user->is_online == 1) {
                                    return 1;
                                }
                            }
                        }

                    }
                }

            }
        }

        return $response;

    }

} 