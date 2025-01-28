<x-mail::message>
Hi {{ $user->full_name }},

You recently requested to reset your account password. Please click here to change your password or click the Reset Password button below.

<x-mail::button :url="route('reset-password', $code)">
Reset Password
</x-mail::button>

If you did not request a password reset, please ignore this message.

If you have any questions or concerns, please don't hesitate to contact us.

Best Regards,

Sigma Health
</x-mail::message>

