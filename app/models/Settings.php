<?php

/**
 * Settings
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Settings whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Settings whereKey($value) 
 * @method static \Illuminate\Database\Query\Builder|\Settings whereValue($value) 
 * @method static \Illuminate\Database\Query\Builder|\Settings whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Settings whereUpdatedAt($value) 
 */
class Settings extends Eloquent {

    protected $table="settings";

} 