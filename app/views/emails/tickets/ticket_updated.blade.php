<tr>
    <td bgcolor="#ffffff" style="padding: 15px;">

        <p style="font-family: Arial, sans-serif; font-size: 15px;">Dear {{{ $customer->name }}},</p>

        <h4 style="font-family:Arial,san-serif;margin:0 0 5px 0;">Your ticket #{{$ticket->id}} has been updated , Status {{$ticket->status_txt}}</h4>

        <p>Your ticket has been updated by one of our operator . Response below</p>

        <strong>Response</strong>
        <p>Operator Name : {{{$operator->name}}}</p>
        <p>Description : {{$operator_message->message}}</p>

        <strong>Ticket Info</strong>
        <p>Subject : {{{$ticket->subject}}}</p>
        <p>Description : {{{$ticket->description}}}</p>
        <p>Status : {{{$ticket->status_txt}}}</p>
        <p>File Attached : {{isset($has_attachment)&&$has_attachment==1?"Yes":"No Attachments"}}</p>

        <p style="font-family: Arial, sans-serif; font-size: 15px;">Thank you, <br />
            ~ Customer Support</p>

    </td>
</tr>