<div class="flex flex-col justify-between w-64 h-full min-h-[90vh] bg-white border-r">

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
