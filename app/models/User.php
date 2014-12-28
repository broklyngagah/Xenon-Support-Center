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

	static function getWelcomeFields($is_fake=false,$user_id = 0,$raw_password = "",$company_id = 0){

		if(!$is_fake) {

			$user = User::find($user_id);
			$company = Company::where('id',$company_id)->first();

			$mailer_extra = [
				'name' => $user->name,
				'email' => $user->email,
				'password' => $raw_password,
				'company_name' => $company->name,
				'company_description' => $company->description,
				'company_domain' => $company->domain,
				'company_logo' => $company->logo,
			];

		}else{
			$mailer_extra = [
				'name' => "Imran",
				'email' => "shellprog@gmail.com",
				'password' => "admin",
				'company_name' => "KODEINFO",
				'company_description' => "We are a small and dedicated team of designers/developers. This is our web design and development focused blog.We focus on pushing the boundaries of standards based web technologies.",
				'company_domain' => "http://www.kodeinfo.com",
				'company_logo' => "http://kodeinfo.com/img/shortlogo.png",
			];
		}

		return $mailer_extra;

	}

	static function getActivateFields($is_fake=false,$user_id = 0,$company_id = 0){

		if(!$is_fake) {

			$user = User::find($user_id);
			$company = Company::where('id',$company_id)->first();

			$mailer_extra = [
				'name' => $user->name,
				'email' => $user->email,
				'user_id' => $user->id,
				'activation_code' => $user->activation_code,
				'activation_url' => URL::to('/')."/activate/".$user->id."/".$user->activation_code,
				'company_name' => $company->name,
				'company_description' => $company->description,
				'company_domain' => $company->domain,
				'company_logo' => $company->logo,
			];

		}else{
			$mailer_extra = [
				'name' => "Imran",
				'email' => "shellprog@gmail.com",
				'user_id' => 1,
				'activation_code' => "some-fake-activation-code",
				'activation_url' => URL::to('/')."/activate/1/some-fake-activation-code",
				'company_name' => "KODEINFO",
				'company_description' => "We are a small and dedicated team of designers/developers. This is our web design and development focused blog.We focus on pushing the boundaries of standards based web technologies.",
				'company_domain' => "http://www.kodeinfo.com",
				'company_logo' => "http://kodeinfo.com/img/shortlogo.png",
			];
		}

		return $mailer_extra;

	}

	static function getResetPasswordFields($is_fake=false,$user_id = 0){

		if(!$is_fake) {

			$user = User::find($user_id);

			$mailer_extra = [
				'name' => $user->name,
				'email' => $user->email,
				'reset_code' => $user->reset_password_code,
				'reset_url' => URL::to('/')."/reset/".$user->email."/".urlencode($user->reset_password_code)
			];

		}else{
			$mailer_extra = [
				'name' => "Imran",
				'email' => "shellprog@gmail.com",
				'reset_code' => 1,
				'reset_url' => URL::to('/')."/reset/shellprog@gmail.com/fake-reset-password-code"
			];
		}

		return $mailer_extra;

	}

	static function getPasswordChangedFields($is_fake=false,$user_id = 0){

		if(!$is_fake) {

			$user = User::find($user_id);

			$mailer_extra = [
				'name' => $user->name
			];

		}else{
			$mailer_extra = [
				'name' => "Imran"
			];
		}

		return $mailer_extra;

	}

} 