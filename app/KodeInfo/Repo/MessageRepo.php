<?php namespace KodeInfo\Repo;

use MessageThread;
use ThreadMessages;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MessageRepo
{

    protected $table = 'users';
    protected $hidden = [];

    function createNewThread($sender_id,$message,$send_message_id=false){

        $msg_thread = new MessageThread();
        $msg_thread->sender_id = $sender_id;
        $msg_thread->operator_id = 0;
        $msg_thread->save();

        $msg = new ThreadMessages();
        $msg->thread_id = $msg_thread->id;
        $msg->sender_id = $sender_id;
        $msg->message = $message;
        $msg->save();

        if($send_message_id){
            return [
                'thread_id' => $msg_thread->id,
                'msg_id' => $msg->id
            ];
        }

        return $msg_thread->id;
    }

    function getMessages($thread_id,$operator_id){
        try {
            $thread = MessageThread::where('id',$thread_id)->where('operator_id',$operator_id)->first();
            return ThreadMessages::where('thread_id', $thread->id)->get();
        }catch(ModelNotFoundException $e){
            return [];
        }
    }

    function sendNewMessage($thread_id,$sender_id,$body)
    {
        if(sizeof(MessageThread::where('id',$thread_id)->get())>0){
            return $this->replyToMessage($thread_id,$sender_id,$body);
        }
        else{
            //$this->createNewThread();
        }
    }

    function replyToMessage($thread_id,$sender_id,$message)
    {

        return DB::transaction(function() use ($thread_id,$sender_id,$message)
        {

            $msg = new ThreadMessages();
            $msg->thread_id = $thread_id;
            $msg->sender_id = $sender_id;
            $msg->message = $message;
            $msg->save();

            return $msg;

        });
    }
}