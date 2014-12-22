<?php

/**
 * CannedMessages
 *
 * @property integer $id
 * @property integer $operator_id
 * @property string $message
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $company_id
 * @property integer $department_id
 * @method static \Illuminate\Database\Query\Builder|\CannedMessages whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\CannedMessages whereOperatorId($value)
 * @method static \Illuminate\Database\Query\Builder|\CannedMessages whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\CannedMessages whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\CannedMessages whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\CannedMessages whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\CannedMessages whereDepartmentId($value)
 */
class CannedMessages extends Eloquent {

    protected $table="canned_messages";


} 