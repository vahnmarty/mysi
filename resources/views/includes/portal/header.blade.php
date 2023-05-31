<header class="relative">
    <div class="py-6 bg-primary-red">
        <div class="max-w-6xl px-8 mx-auto">
            <div class="flex items-center justify-between">
                <div class="flex">
                    <img src="{{ asset('img/logo-white.png') }}" class="w-auto h-16" alt="">
                    <div class="pl-8">
                        <h4 class="text-3xl font-bold text-white font-heading">My SI</h4>
                        <p class="text-xl text-white font-heading">St. Ignatius College Prepatory</p>
                    </div>
                </div>
                <div class="flex items-center">
                    
                    <div class="relative flex-shrink-0 ml-5 rounded-md">
                    
                        <x-dropdown width="96">
                            <x-slot name="trigger">
                                <button type="button"  class="p-2 text-white bg-red-900 rounded-sm hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                                    <x-heroicon-o-chat class="w-4 h-4"/>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <div class="p-4 min-h-[10rem]">
                                    <div class="flex justify-between">
                                        <h4 class="text-sm font-bold">Inbox</h4>
                                        <a href="{{ url('inbox') }}" class="text-xs font-bold text-purple-600">View All</a>
                                    </div>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
    
                    <div class="relative flex-shrink-0 ml-5 rounded-md">
                        
                        <x-dropdown width="96">
                            <x-slot name="trigger">
                                <button type="button"  class="p-2 text-white bg-red-900 rounded-sm hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">
                                    <x-heroicon-o-bell class="w-4 h-4"/>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <div class="p-4 min-h-[10rem]">
                                    <h4 class="text-sm font-bold">Notifications</h4>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
    
                    
    
                    <!-- Profile dropdown -->
                    <div class="relative flex-shrink-0 ml-5 rounded-md">
                        
                        <x-dropdown>
                            <x-slot name="trigger">
                                <button class="flex p-1 transition duration-200 ease-in-out hover:bg-red-900" type="button">
                                    <div class="mr-3 text-right">
                                        <p class="text-sm text-white">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-100">{{ Auth::user()->email }}</p>
                                    </div>
                                    <div class="p-2 bg-gray-500 rounded-sm">
                                        <x-heroicon-o-user class="w-5 h-5 text-white"/>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <div class="py-3">
                                    <div class="px-6 py-3 hover:bg-gray-100">
                                        <a href="{{ url('user/profile') }}" class="flex gap-3 text-xs">
                                            <x-heroicon-o-user-circle class="w-4 h-4"/>
                                            Profile
                                        </a>
                                    </div>
                                    <div class="px-6 py-3 hover:bg-gray-100">
                                        <a href="#" class="flex gap-3 text-xs" onclick="document.querySelector('#form_logout').submit()">
                                            <x-heroicon-o-logout class="w-4 h-4"/>
                                            Log out
                                        </a>
                                            <form action="{{ route('logout') }}" method="POST" id="form_logout" class="hidden">
                                                @csrf
                                            </form>
                                    </div>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>