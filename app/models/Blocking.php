<?php

/**
 * Blocking
 *
 * @property integer $id
 * @property string $ip_address
 * @property boolean $should_block_chat
 * @property boolean $should_block_tickets
 * @property boolean $should_block_login
 * @property boolean $should_block_web_access
 * @property boolean $full_blocking
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Blocking whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Blocking whereIpAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\Blocking whereShouldBlockChat($value)
 * @method static \Illuminate\Database\Query\Builder|\Blocking whereShouldBlockTickets($value)
 * @method static \Illuminate\Database\Query\Builder|\Blocking whereShouldBlockLogin($value)
 * @method static \Illuminate\Database\Query\Builder|\Blocking whereShouldBlockWebAccess($value)
 * @method static \Illuminate\Database\Query\Builder|\Blocking whereFullBlocking($value)
 * @method static \Illuminate\Database\Query\Builder|\Blocking whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Blocking whereUpdatedAt($value)
 */
class Blocking extends Eloquent {

    protected $table="blocking";

} 