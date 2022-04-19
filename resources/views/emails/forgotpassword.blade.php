@component('mail::message')
# Forgot Password

Your Password Recovery Code is : {{ $code }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
