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
 * @property integer $group_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\UsersGroups whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\UsersGroups whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\UsersGroups whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\UsersGroups whereUpdatedAt($value)
 */

class UsersGroups extends Eloquent {

    protected $table="users_groups";

    public $timestamps=false;

} 