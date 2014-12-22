<?php

/**
 * OperatorsDepartment
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $department_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\OperatorsDepartment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\OperatorsDepartment whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\OperatorsDepartment whereDepartmentId($value)
 * @method static \Illuminate\Database\Query\Builder|\OperatorsDepartment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\OperatorsDepartment whereUpdatedAt($value)
 */
class OperatorsDepartment extends Eloquent {

    protected $table="operators_department";

} 