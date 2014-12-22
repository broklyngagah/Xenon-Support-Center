<tr>
    <td bgcolor="#ffffff" style="padding: 15px;">

        <h4 style="font-family:Arial,san-serif;margin:0 0 5px 0;">Thanks for contacting support</h4>

        <p style="font-family: Arial, sans-serif; font-size: 15px;">Dear {{{ $customer->name }}},</p>

        <p>Please confirm your ticket have been created , one of our operator will get back to you as soon as possible</p>

        <strong>Ticket Info</strong>
        <p>Status : {{{$ticket->status_txt}}}</p>
        <p>Subject : {{{$ticket->subject}}}</p>
        <p>Description : {{{$ticket->description}}}</p>
        <p>File Attached : {{isset($has_attachment)&&$has_attachment==1?"Yes":"No Attachments"}}</p>

        <p style="font-family: Arial, sans-serif; font-size: 15px;">Thank you, <br />
            ~ Customer Support</p>

    </td>
</tr>