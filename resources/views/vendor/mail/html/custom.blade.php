<x-mail::layout>
{{-- Header --}}

<x-slot:header>
<div style="margin-top: 15px; margin-bottom: 15px; width: 570px;">
    <img src="{{ asset('img/logo.png') }}" style="width: 64px; height: auto; margin:0 auto; display: block">
</div>
</x-slot:header>

{{-- Body --}}
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
