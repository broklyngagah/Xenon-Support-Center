<?php

/**
 * MessageThread
 *
 * @property integer $id
 * @property integer $sender_id
 * @property integer $operator_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\MessageThread whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\MessageThread whereSenderId($value)
 * @method static \Illuminate\Database\Query\Builder|\MessageThread whereOperatorId($value)
 * @method static \Illuminate\Database\Query\Builder|\MessageThread whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\MessageThread whereUpdatedAt($value)
 */
class MessageThread extends Eloquent {

    protected $table="message_threads";

    public static function getClientMessages($thread_id,$last_message_id){

        $thread = MessageThread::find($thread_id);

        $thread->user = User::find($thread->sender_id);
        $thread->operator = User::find($thread->operator_id);

        if($last_message_id > 0)
            $messages = ThreadMessages::where('thread_id',$thread_id)->where('id','>',$last_message_id)->get();
        else
            $messages = ThreadMessages::where('thread_id',$thread_id)->get();

        foreach($messages as $message){
            $message->user = User::find($message->sender_id);
        }

        $message_str = View::make('conversations.stub-client-message',["messages"=>$messages,"thread"=>$thread])->render();

        if(sizeof($messages)>0){
            $last_message_id = $messages[sizeof($messages)-1]->id;
        }else{
            $last_message_id = $last_message_id;
        }

        return ["messages_arr"=>$messages,'last_message_id'=>$last_message_id,"messages_str"=>$message_str];

    }

    public static function getServerMessages($thread_id,$last_message_id){

        $thread = MessageThread::find($thread_id);

        $thread->user = User::find($thread->sender_id);
        $thread->operator = User::find($thread->operator_id);

        if($last_message_id > 0)
            $messages = ThreadMessages::where('thread_id',$thread_id)->where('id','>',$last_message_id)->get();
        else
            $messages = ThreadMessages::where('thread_id',$thread_id)->get();

        foreach($messages as $message){
            $message->user = User::find($message->sender_id);
        }

        $message_str = View::make('conversations.stub-server-message',["messages"=>$messages,"thread"=>$thread])->render();

        if(sizeof($messages)>0){
            $last_message_id = $messages[sizeof($messages)-1]->id;
        }else{
            $last_message_id = $last_message_id;
        }

        return ["messages_arr"=>$messages,'last_message_id'=>$last_message_id,"messages_str"=>$message_str];

    }


    public static function getTicketMessages($thread_id,$last_message_id){

        ThreadMessages::where('thread_id',$thread_id)->where('sender_id',0)->update(['sender_id'=>Auth::user()->id]);

        $thread = MessageThread::find($thread_id);

        if($last_message_id > 0)
            $messages = ThreadMessages::where('thread_id',$thread_id)->where('id','>',$last_message_id)->get();
        else
            $messages = ThreadMessages::where('thread_id',$thread_id)->get();

        foreach($messages as $message){

            $message->user = User::find($message->sender_id);

            $attachment = TicketAttachments::where('message_id',$message->id)->where('thread_id',$thread_id)->first();

            $message->attachment = $attachment;
        }

        $message_str = View::make('tickets.stub-ticket-message',["messages"=>$messages,"thread"=>$thread])->render();

        if(sizeof($messages)>0){
            $last_message_id = $messages[sizeof($messages)-1]->id;
        }else{
            $last_message_id = $last_message_id;
        }

        return ["messages_arr"=>$messages,'last_message_id'=>$last_message_id,"messages_str"=>$message_str];

    }
} 