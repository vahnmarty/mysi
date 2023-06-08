@props(['active' => false])

<li class="px-8 py-1 text-sm transition  {{ $active ? 'border-green-400 border-r-2 bg-gray-200' : 'hover:bg-gray-200' }}">
    <a {{ $attributes }} class="inline-flex items-center w-full gap-3">
        {{ $icon }}
        <span>{{ $slot }}</span>
    </a>
</li>