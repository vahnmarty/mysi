@props(['active' => false])

<li x-data="{ open: false }"
    class="transition relative text-sm  {{ $active ? 'border-green-400 border-r-2 bg-gray-300' : 'hover:bg-gray-200' }}">
    <button x-on:click="open = !open" type="button" class="inline-flex w-full gap-3 px-8 pt-2 pb-2 ">
        {{ $icon }}
        <span>{{ $slot }}</span>
    </button>
    <ul x-show="open" x-cloak>
       {{ $menu }}
    </ul>
</li>