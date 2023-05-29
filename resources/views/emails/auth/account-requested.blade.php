<x-mail::message>
# Welcome to MySI
 
To proceed in creating your account, please click the button to verify your account.
 
<x-mail::button :url="$url">
Verify Account
</x-mail::button>
 
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>