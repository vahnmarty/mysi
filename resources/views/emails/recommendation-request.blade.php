<x-mail::message>
## Hello {{ $recommendation->recommender_first_name }},

You have been invited by **{{ $recommendation->guardian->getFullName() }}** to submit a recommendation for **{{ $recommendation->child->first_name }}** as part of [his/her] application to St. Ignatius College Preparatory.

@component('mail::panel')
{{ $recommendation->message }}
@endcomponent


To write a recommendation, please click on this link:  [{{ url('recommendation') }}]({{ route('recommendation-form', $recommendation->uuid) }}) 

**Note** St. Ignatius College Preparatory must receive all supplemental recommendations by Wednesday, January 10, 2024 at 11:59 PT.

<br>

## Regards,

MySI Portal Admin
</x-mail::message>