@props(['active' => false])

<li x-data="{ open: false }"
    class="transition relative text-sm  {{ $active ? 'border-green-400 border-r-2 bg-gray-300' : 'hover:bg-gray-200' }}">
    <button x-on:click="open = !open" type="button" class="flex justify-between w-full gap-3 pt-2 pb-2 pl-8 pr-3 ">
        <div class="inline-flex gap-3">
            {{ $icon }}
            <span>{{ $slot }}</span>
        </div>
        <div>
            <x-heroicon-s-menu class="w-4 h-4"/>
        </div>
    </button>
    <ul x-show="open" x-cloak>
       {{ $menu }}
    </ul>
</li>