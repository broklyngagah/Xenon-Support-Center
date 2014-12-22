<?php

/**
 * ThreadMessages
 *
 * @property integer $id
 * @property integer $thread_id
 * @property integer $sender_id
 * @property string $message
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\ThreadMessages whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ThreadMessages whereThreadId($value)
 * @method static \Illuminate\Database\Query\Builder|\ThreadMessages whereSenderId($value)
 * @method static \Illuminate\Database\Query\Builder|\ThreadMessages whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\ThreadMessages whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\ThreadMessages whereUpdatedAt($value)
 */
class ThreadMessages extends Eloquent {

    protected $table="thread_messages";


} 