<tr>
    <td bgcolor="#ffffff" style="padding: 15px;">

        <h4 style="font-family:Arial,san-serif;margin:0 0 5px 0;">Thanks for register</h4>

        <p style="font-family: Arial, sans-serif; font-size: 15px;">Dear {{{ $name }}},</p>

        <p>Please confirm your email address and activate your account by <a href="{{ URL::to('/') }}/activate/{{$user_id}}/{{$activation_code}}">clicking here</a></p>

        <p style="font-family: Arial, sans-serif; font-size: 15px;">Thank you, <br />
            ~ Customer Support</p>

    </td>
</tr>