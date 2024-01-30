<header class="relative">
    <div class="py-2 lg:py-6 {{ has_registered() ? 'bg-primary-blue' : 'bg-primary-red' }}">
        <div class="px-4 mx-auto lg:px-8">
            <div class="flex items-center justify-between">
                

                <div x-data="{ open: false }" 
                    class="px-4 lg:hidden">
                    <button x-on:click="open = !open; $dispatch('open-menu')"
                        x-show="!open"
                        type="button">
                        <x-heroicon-s-menu class="w-5 h-5 text-gray-300"/>
                    </button>
                    <button x-on:click="open = !open; $dispatch('open-menu')"
                        x-show="open"
                        x-cloak
                        type="button">
                        <x-heroicon-s-x class="w-5 h-5 text-gray-300"/>
                    </button>
                </div>

                <div class="flex items-center lg:items-start">

                    <img src="{{ asset('img/logo-white.png') }}" class="object-cover w-auto h-10 lg:w-auto lg:h-16">


                    <div class="hidden pl-8 lg:block">
                        <a href="{{ url('/') }}">
                            <h4 class="text-4xl font-extrabold text-white font-heading">MySI Portal</h4>
                            <p class="text-xl text-white font-heading">St. Ignatius College Preparatory</p>
                        </a>
                    </div>
                    
                </div>

                <div class="flex items-center">
                    
                    <!-- <div class="relative flex-shrink-0 ml-5 rounded-md">
                    
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
                    </div> -->
    
                    <!-- <div class="relative flex-shrink-0 ml-5 rounded-md">
                        
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
                    </div> -->
    
                    
    
                    <!-- Profile dropdown -->
                    <div class="relative flex-shrink-0 ml-5 rounded-md">
                        
                        <x-dropdown>
                            <x-slot name="trigger">
                                <button class="flex p-1 transition duration-200 ease-in-out hover:bg-red-900" type="button">
                                    <div class="hidden mr-3 text-right lg:block">
                                        <p class="text-sm text-white">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-100">{{ Auth::user()->username ? '@' . Auth::user()->username : Auth::user()->email }}</p>
                                    </div>
                                    <div class="p-1.5 bg-gray-500 rounded-sm lg:p-2">
                                        <x-heroicon-o-user class="w-4 h-4 text-white lg:w-5 lg:h-5"/>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <div class="py-3">
                                    <div class="hover:bg-gray-100">
                                        <a href="{{ url('profile') }}" class="flex gap-3 px-6 py-3 text-xs ">
                                            <x-heroicon-o-user-circle class="w-4 h-4"/>
                                            Profile
                                        </a>
                                    </div>
                                    @if(Auth::user()->isImpersonated())
                                    <div class="p-4">
                                        <a href="{{ url('impersonator/logout') }}" class="flex gap-3 px-3 py-3 text-xs text-primary-red bg-primary-red/10 hover:bg-primary-red/50">
                                            <x-heroicon-o-x class="w-4 h-4"/>
                                            Exit
                                        </a>
                                    </div>
                                    @else
                                    <div class="hover:bg-gray-100">
                                        <a href="#" class="flex gap-3 px-6 py-3 text-xs " onclick="document.querySelector('#form_logout').submit()">
                                            <x-heroicon-o-logout class="w-4 h-4"/>
                                            Log out
                                        </a>
                                            <form action="{{ route('logout') }}" method="POST" id="form_logout" class="hidden">
                                                @csrf
                                            </form>
                                    </div>
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="px-8 py-2 bg-red-800 lg:py-2">
        <div class="flex justify-between">
            <div class="flex items-center flex-1 gap-8 text-xs text-white">
                <a href="">Home</a>
                <a href="">Profile</a>

                <x-flyout-menu button="Information">
                    <div class="w-56 p-4 text-sm font-semibold leading-6 text-gray-900 bg-white shadow-lg shrink rounded-xl ring-1 ring-gray-900/5">
                        <a href="#" class="block p-2 hover:text-indigo-600">Children Info</a>
                      </div>
                </x-flyout-menu>

                <x-flyout-menu button="Activities">
                    <div class="w-56 p-4 text-sm font-semibold leading-6 text-gray-900 bg-white shadow-lg shrink rounded-xl ring-1 ring-gray-900/5">
                        <a href="#" class="block p-2 hover:text-indigo-600">Eight Grades</a>
                      </div>
                </x-flyout-menu>

            </div>
        </div>
    </div> -->
</header>