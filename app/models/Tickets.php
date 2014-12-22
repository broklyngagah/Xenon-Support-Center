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

    static function resolveStatus($ticket_id){

        $ticket = Tickets::find($ticket_id);

        $status_txt = "New";

        if($ticket->status == Tickets::TICKET_NEW){
            $status_txt = "New";
        }

        if($ticket->status == Tickets::TICKET_PENDING){
            $status_txt = "Pending";
        }

        if($ticket->status == Tickets::TICKET_RESOLVED){
            $status_txt = "Resolved";
        }

        return $status_txt;

    }

} 