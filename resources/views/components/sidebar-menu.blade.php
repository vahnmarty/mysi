@props(['active' => false])

<li x-data="{ open: false }"
    class="transition relative   {{ $active ? 'border-green-400 border-r-2 bg-gray-100' : 'hover:bg-gray-200' }}">
    <button x-on:click="open = !open" type="button" class="inline-flex w-full gap-3 px-8 pt-3 pb-3 ">
        {{ $icon }}
        <span>{{ $slot }}</span>
    </button>
    <ul x-show="open" x-cloak>
       {{ $menu }}
    </ul>
</li>