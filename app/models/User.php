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
 * @property string $password
 * @property string $avatar
 * @property boolean $show_avatar
 * @property string $bio
 * @property string $gender
 * @property string $mobile_no
 * @property string $country
 * @property string $reset_password_code
 * @property string $remember_token
 * @property string $activated
 * @property string $activation_code
 * @property string $activated_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $birthday
 * @property string $timezone
 * @property string $permissions
 * @property boolean $is_online
 * @method static \Illuminate\Database\Query\Builder|\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereShowAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereBio($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereMobileNo($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCountry($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereResetPasswordCode($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereActivated($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereActivationCode($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereActivatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereBirthday($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereTimezone($value)
 * @method static \Illuminate\Database\Query\Builder|\User wherePermissions($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereIsOnline($value)
 */

class User extends Eloquent {

    protected $table="users";
	
	const USER_TYPE_OPERATOR = 1;
	const USER_TYPE_CUSTOMER = 2;
	const USER_TYPE_ADMIN = 3;
	const USER_TYPE_DEPARTMENT_ADMIN = 4;
	const USER_TYPE_SUPER_ADMIN = 5;

    public $timestamps=false;

} 