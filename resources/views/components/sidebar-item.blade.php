@props(['active' => false])

<li class="px-8 py-3 transition    {{ $active ? 'border-green-400 border-r-2 bg-gray-100' : 'hover:bg-gray-200' }}">
    <a {{ $attributes }} class="inline-flex w-full gap-3">
        {{ $icon }}
        <span>{{ $slot }}</span>
    </a>
</li>