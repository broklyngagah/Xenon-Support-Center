<?php

/**
 * TicketAttachments
 *
 * @property integer $id
 * @property integer $thread_id
 * @property integer $message_id
 * @property boolean $has_attachment
 * @property string $attachment_path
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\TicketAttachments whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\TicketAttachments whereThreadId($value)
 * @method static \Illuminate\Database\Query\Builder|\TicketAttachments whereMessageId($value)
 * @method static \Illuminate\Database\Query\Builder|\TicketAttachments whereHasAttachment($value)
 * @method static \Illuminate\Database\Query\Builder|\TicketAttachments whereAttachmentPath($value)
 * @method static \Illuminate\Database\Query\Builder|\TicketAttachments whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\TicketAttachments whereUpdatedAt($value)
 */
class TicketAttachments extends Eloquent {

    protected $table="tickets_attachment";

} 