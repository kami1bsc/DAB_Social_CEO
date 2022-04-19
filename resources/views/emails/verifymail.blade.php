@component('mail::message')
# Verify Your Email

Click on the Button to Verify Your Email!

@component('mail::button', ['url' => $link])
Verify Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
