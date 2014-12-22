<?php

/**
 * ClosedConversations
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $thread_id
 * @property integer $operator_id
 * @property string $requested_on
 * @property string $started_on
 * @property string $token
 * @property string $ended_on
 * @method static \Illuminate\Database\Query\Builder|\ClosedConversations whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ClosedConversations whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\ClosedConversations whereThreadId($value)
 * @method static \Illuminate\Database\Query\Builder|\ClosedConversations whereOperatorId($value)
 * @method static \Illuminate\Database\Query\Builder|\ClosedConversations whereRequestedOn($value)
 * @method static \Illuminate\Database\Query\Builder|\ClosedConversations whereStartedOn($value)
 * @method static \Illuminate\Database\Query\Builder|\ClosedConversations whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\ClosedConversations whereEndedOn($value)
 */
class ClosedConversations extends Eloquent {

    protected $table="closed_conversations";
    public $timestamps=false;


} 