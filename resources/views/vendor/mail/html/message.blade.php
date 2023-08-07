<x-mail::layout>
{{-- Header --}}

{{-- Body --}}
@isset($logo)
{{ $logo }}
@else
<img src="{{ asset('img/logo.png') }}" style="width: 64px; height: auto; margin-top: 15px; margin-bottom: 15px; margin-right: auto; display: block">
@endisset

{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{{ $subcopy }}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
