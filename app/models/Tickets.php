<?php

/**
 * Tickets
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $priority
 * @property integer $company_id
 * @property integer $department_id
 * @property string $subject
 * @property string $description
 * @property boolean $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $thread_id
 * @method static \Illuminate\Database\Query\Builder|\Tickets whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Tickets whereCustomerId($value)
 * @method static \Illuminate\Database\Query\Builder|\Tickets wherePriority($value)
 * @method static \Illuminate\Database\Query\Builder|\Tickets whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\Tickets whereDepartmentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Tickets whereSubject($value)
 * @method static \Illuminate\Database\Query\Builder|\Tickets whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Tickets whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Tickets whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Tickets whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Tickets whereThreadId($value)
 * @property integer $operator_id
 * @property string $requested_on
 * @property string $started_on
 * @method static \Illuminate\Database\Query\Builder|\Tickets whereOperatorId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Tickets whereRequestedOn($value) 
 * @method static \Illuminate\Database\Query\Builder|\Tickets whereStartedOn($value) 
 */
class Tickets extends Eloquent {

    const TICKET_NEW = 1;
    const TICKET_PENDING = 2;
    const TICKET_RESOLVED = 3;

    const PRIORITY_LOW = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH = 3;
    const PRIORITY_URGENT = 4;

    protected $table="tickets";
    public $timestamps=false;

    static function getCreatedFields($is_fake=false,$ticket_id = 0,$msg_id = 0){

        if(!$is_fake) {
            $ticket = Tickets::find($ticket_id);
            $thread_message = ThreadMessages::where('id', $msg_id)->first();
            $ticket_attachment = TicketAttachments::where('message_id', $msg_id)->first();

            $customer = User::where('id', $ticket->customer_id)->first();
            $company = Company::where('id', $ticket->company_id)->first();
            $department = Department::where('id', $ticket->department_id)->first();

            $mailer_extra = [
                'ticket_id' => $ticket->id,
                'ticket_subject' => $ticket->subject,
                'ticket_description' => $ticket->description,
                'ticket_status' => $ticket->status,
                'ticket_status_txt' => self::resolveStatus($ticket->status),
                'ticket_priority' => $ticket->priority,
                'ticket_priority_txt' => self::resolveStatus($ticket->priority),
                'company_name' => $company->name,
                'company_description' => $company->description,
                'company_domain' => $company->domain,
                'company_logo' => $company->logo,
                'department_name' => $department->name,
                'has_attachment' => $ticket_attachment->has_attachment,
                'attachment_path' => $ticket_attachment->attachment_path,
                'receiver_name' => $customer->name,
                'receiver_email' => $customer->email
            ];
        }else{
            $mailer_extra = [
                'ticket_id' => 1,
                'ticket_subject' => "How can i use contact us form",
                'ticket_description' => "Hi , Sir how can i use contact us form",
                'ticket_status' => 1,
                'ticket_status_txt' => self::resolveStatus(1),
                'ticket_priority' => 1,
                'ticket_priority_txt' => self::resolveStatus(1),
                'company_name' => "KODEINFO",
                'company_description' => "We are a small and dedicated team of designers/developers. This is our web design and development focused blog.We focus on pushing the boundaries of standards based web technologies.",
                'company_domain' => "http://www.kodeinfo.com",
                'company_logo' => "http://kodeinfo.com/img/shortlogo.png",
                'department_name' => "General Queries",
                'has_attachment' => false,
                'attachment_path' => "",
                'receiver_name' => "Imran",
                'receiver_email' => "shellprog@gmail.com"
            ];
        }

        return $mailer_extra;

    }

    static function getUpdatedFields($is_fake=false,$ticket_id = 0,$msg_id = 0){


        if(!$is_fake) {
            $ticket = Tickets::where('id', $ticket_id)->first();
            $thread_message = ThreadMessages::where('id', $msg_id)->first();
            $ticket_attachment = TicketAttachments::where('message_id', $msg_id)->first();

            $customer = User::where('id', $ticket->customer_id)->first();
            $operator = User::where('id', $ticket->operator_id)->first();
            $company = Company::where('id', $ticket->company_id)->first();
            $department = Department::where('id', $ticket->department_id)->first();
            $receiver = Input::get('user_id') == $ticket->operator_id ? $customer : $operator;

            $mailer_extra = [
                'ticket_id' => $ticket->id,
                'ticket_subject' => $ticket->subject,
                'ticket_description' => $ticket->description,
                'ticket_status' => $ticket->status,
                'ticket_status_txt' => self::resolveStatus($ticket->status),
                'ticket_priority' => $ticket->priority,
                'ticket_priority_txt' => self::resolveStatus($ticket->priority),
                'company_name' => $company->name,
                'company_description' => $company->description,
                'company_domain' => $company->domain,
                'company_logo' => $company->logo,
                'department_name' => $department->name,
                'has_attachment' => $ticket_attachment->has_attachment,
                'attachment_path' => $ticket_attachment->attachment_path,
                'updated_message' => $thread_message->message,
                'receiver_name' => $receiver->name,
                'receiver_email' => $receiver->email
            ];
        }else{
            $mailer_extra = [
                'ticket_id' => 1,
                'ticket_subject' => "How can i use contact us form",
                'ticket_description' => "Hi , Sir how can i use contact us form",
                'ticket_status' => 1,
                'ticket_status_txt' => self::resolveStatus(1),
                'ticket_priority' => 1,
                'ticket_priority_txt' => self::resolveStatus(1),
                'company_name' => "KODEINFO",
                'company_description' => "We are a small and dedicated team of designers/developers. This is our web design and development focused blog.We focus on pushing the boundaries of standards based web technologies.",
                'company_domain' => "http://www.kodeinfo.com",
                'company_logo' => "http://kodeinfo.com/img/shortlogo.png",
                'department_name' => "General Queries",
                'has_attachment' => false,
                'attachment_path' => "",
                'updated_message' => "This is a updated message from customer/operator",
                'receiver_name' => "Imran",
                'receiver_email' => "shellprog@gmail.com"
            ];
        }

        return $mailer_extra;

    }

    static function resolveStatus($status){

        $status_txt = "New";

        if($status == Tickets::TICKET_NEW){
            $status_txt = "New";
        }

        if($status == Tickets::TICKET_PENDING){
            $status_txt = "Pending";
        }

        if($status == Tickets::TICKET_RESOLVED){
            $status_txt = "Resolved";
        }

        return $status_txt;

    }

    static function resolvePriority($priority){

        $priority_txt = "Low";

        if($priority == Tickets::PRIORITY_LOW){
            $priority_txt = "Low";
        }

        if($priority == Tickets::PRIORITY_MEDIUM){
            $priority_txt = "Medium";
        }

        if($priority == Tickets::PRIORITY_HIGH){
            $priority_txt = "High";
        }

        if($priority == Tickets::PRIORITY_URGENT){
            $priority_txt = "Urgent";
        }

        return $priority_txt;

    }

} 