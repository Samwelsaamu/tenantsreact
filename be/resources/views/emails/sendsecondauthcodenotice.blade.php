@component('mail::message')
# Hello!

Greatings {{ $fullname }}, Your Code is: {{ $code }}

# 2-Step Authetication Code

We have received your request to Login.

You can use the following code to Confirm its you:

{{ $code }}

The Above Code Expires after Five(5) Minutes.

If you did not try to sign in, no further action is required.


Regards,<br>
{{ config('app.name') }}
@endcomponent
