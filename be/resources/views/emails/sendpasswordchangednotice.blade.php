@component('mail::message')
# Hello!

Greatings {{ $fullname }}

# Password Changed Successfully!.

Your Account Password was successfully Changed.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
