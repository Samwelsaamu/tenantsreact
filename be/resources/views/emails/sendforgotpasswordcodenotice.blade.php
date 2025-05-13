@component('mail::message')
# Hello!
Greatings {{ $fullname }}, Your Code is: {{ $code }}

# Reset Password Code

We have received your request to Reset your account password.

You can use the following code to Reset:

{{ $code }}

The Above Code Expires after five(5) Minutes.

If you did not try to reset password, no further action is required.


Regards,<br>
{{ config('app.name') }}
@endcomponent
