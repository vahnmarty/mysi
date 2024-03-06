<section>
    <div class="hidden lg:flex flex-col w-80 h-full min-h-[90vh] bg-gray-100 border-r">

        @include('includes.portal.sidebar-items')
        
    </div>
    <div x-data="{ open: false }" 
        x-on:open-menu.window="open = !open"
        x-transition
        :class="{ '-translate-x-[100vw]' : !open, 'translate-x-0' : open }"
        class="z-50 fixed inset-0 top-16 w-full min-h-screen -translate-x-[100vw] transition-all duration-300 ease-in-out bg-white lg:hidden">

        @include('includes.portal.sidebar-items')
        
    </div>
</section>