@component('mail::message')
# Hello!
Greatings {{ $fullname }}

# Your Account has been Successfully Verified.!!

You Can use the following link or button to Login.

@component('mail::button', ['url' => $url])
Login Here
@endcomponent


Regards,<br>
{{ config('app.name') }}
@endcomponent
