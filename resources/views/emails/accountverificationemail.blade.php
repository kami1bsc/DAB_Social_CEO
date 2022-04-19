@component('mail::message')
Account Verification Email

Your Account Verification Code is: {{ $code }} <br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
