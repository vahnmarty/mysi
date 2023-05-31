<section>
    <div class="hidden lg:flex flex-col justify-between w-64 h-full min-h-[90vh] bg-white border-r">

        <div class="py-8">
            <ul>
                <x-sidebar-item href="{{ route('dashboard') }}" :active="request()->is('dashboard')">
                    <x-slot name="icon">
                        <x-heroicon-o-home class="w-5 h-5" />
                    </x-slot>
                    Home
                </x-sidebar-item>
            </ul>
        </div>
    
    </div>
    <div x-data="{ open: false }" 
        x-on:open-menu.window="open = !open"
        x-transition
        :class="{ '-translate-x-[100vw]' : !open, 'translate-x-0' : open }"
        class="fixed inset-0 top-16 w-full min-h-screen -translate-x-[100vw] transition-all duration-300 ease-in-out bg-white lg:hidden">

        <div class="py-8">
            <ul>
                <x-sidebar-item href="{{ route('dashboard') }}" :active="request()->is('dashboard')">
                    <x-slot name="icon">
                        <x-heroicon-o-home class="w-5 h-5" />
                    </x-slot>
                    Home
                </x-sidebar-item>
            </ul>
        </div>
        
    </div>
</section>