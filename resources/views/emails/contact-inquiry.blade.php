<x-mail::message>
## Subject: {{ $inquiry->subject }}
## From: {{ $inquiry->account }}

{{ $inquiry->message }}

</x-mail::message>
