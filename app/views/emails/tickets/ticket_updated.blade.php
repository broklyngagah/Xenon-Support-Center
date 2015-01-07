<tr>
    <td bgcolor="#ffffff" style="padding: 15px;">

        <p style="font-family: Arial, sans-serif; font-size: 15px;">Dear {{{ $receiver_name }}},</p>

        <h4 style="font-family:Arial,san-serif;margin:0 0 5px 0;">Your ticket #{{$ticket_id}} has been updated , Status {{$ticket_status_txt}}</h4>

        <p>Your ticket has been updated by one of our operator . Response below</p>

        <strong>Response</strong>
        <p>Description : {{$updated_message}}</p>

        <strong>Ticket Info</strong>
        <p>Subject : {{{$ticket_subject}}}</p>
        <p>Description : {{{$ticket_description}}}</p>
        <p>Status : {{{$ticket_status_txt}}}</p>
        <p>File Attached : {{isset($has_attachment)&&$has_attachment==1?"Yes":"No Attachments"}}</p>

        <p style="font-family: Arial, sans-serif; font-size: 15px;">Thank you, <br />
            ~ {{{$company_name}}}</p>

    </td>
</tr>