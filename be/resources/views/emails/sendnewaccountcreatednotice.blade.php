@component('mail::message')
# Hello!
Greatings {{ $fullname }}

# New Account Created.

We have received a request to create your account.

Please Login or Click the button below and enter your username and password.

@component('mail::button', ['url' => $url])
Login Here
@endcomponent

A code will be sent to Verify your account Email Address:

Your Password is IDNo Given During Registration.

If you did not create an account, no further action is required.



Regards,<br>
{{ config('app.name') }}
@endcomponent
