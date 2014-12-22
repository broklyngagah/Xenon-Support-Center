<tr>
    <td bgcolor="#ffffff" style="padding: 15px;">

        <h4 style="font-family:Arial,san-serif;margin:0 0 5px 0;">Hi {{$name}}</h4>

        <p style="font-family: Arial, sans-serif; font-size: 15px;">To reset your password, <a href="{{ URL::to('/') }}/reset/{{ urlencode($email) }}/{{ urlencode($reset_code) }}">click here.</a></p>

        <p style="font-family: Arial, sans-serif; font-size: 15px;">If you did not request a password reset, you can safely ignore this email - nothing will be changed.</p>

        <p style="font-family: Arial, sans-serif; font-size: 15px;">Thank you, <br />
            ~Customer Service</p>
    </td>
</tr>