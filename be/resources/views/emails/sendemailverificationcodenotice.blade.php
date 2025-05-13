@component('mail::message')
# Hello!
Greatings {{ $fullname }}, Your Code is: {{ $code }}

# Email Verification Code

We have received your request to Verify your account Email.

You can use the following code to Verify:

{{ $code }}

The Above Code Expires after One Hour.

If you did not try to sign in, no further action is required.


Regards,<br>
{{ config('app.name') }}
@endcomponent
