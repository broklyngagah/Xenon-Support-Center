<?php

/**
 * Department
 *
 * @property integer $id
 * @property string $name
 * @property string $permissions
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $company_id
 * @method static \Illuminate\Database\Query\Builder|\Department whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Department whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Department wherePermissions($value)
 * @method static \Illuminate\Database\Query\Builder|\Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Department whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Department whereCompanyId($value)
 */
class Department extends Eloquent {

    protected $table="departments";

    static function permissions($permissions){
        $p_keys = json_decode($permissions);
        $p_texts = Permissions::whereIn('key',$p_keys)->lists('text');
        return implode('</br>',$p_texts);
    }

} 