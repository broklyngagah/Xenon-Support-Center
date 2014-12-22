<?php

/**
 * OnlineUsers
 *
 * @property integer $id
 * @property string $user_id
 * @property integer $thread_id
 * @property integer $operator_id
 * @property boolean $locked_by_operator
 * @property string $requested_on
 * @property string $started_on
 * @property string $token
 * @method static \Illuminate\Database\Query\Builder|\OnlineUsers whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\OnlineUsers whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\OnlineUsers whereThreadId($value)
 * @method static \Illuminate\Database\Query\Builder|\OnlineUsers whereOperatorId($value)
 * @method static \Illuminate\Database\Query\Builder|\OnlineUsers whereLockedByOperator($value)
 * @method static \Illuminate\Database\Query\Builder|\OnlineUsers whereRequestedOn($value)
 * @method static \Illuminate\Database\Query\Builder|\OnlineUsers whereStartedOn($value)
 * @method static \Illuminate\Database\Query\Builder|\OnlineUsers whereToken($value)
 */
class OnlineUsers extends Eloquent {

    protected $table="online_users";
    public $timestamps=false;

    public static function getToken(){
        $token = Str::random();

        if(sizeof(self::where('token',$token)->get())>0){
            return self::getToken();
        }else{
            return $token;
        }
    }

} 