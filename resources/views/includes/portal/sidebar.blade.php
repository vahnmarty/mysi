<section>
    <div class="hidden lg:flex flex-col  w-64 h-full min-h-[90vh] bg-gray-100 border-r">

        <div class="py-8">
            <ul>
                <x-sidebar-item href="{{ route('dashboard') }}" :active="request()->is('dashboard')">
                    <x-slot name="icon">
                        <x-heroicon-o-home class="w-5 h-5" />
                    </x-slot>
                    Home
                </x-sidebar-item>
                <x-sidebar-menu  :active="request()->is('profile*')">
                    <x-slot name="icon">
                        <x-heroicon-o-user class="w-5 h-5" />
                    </x-slot>
                    Profile
                    <x-slot name="menu">
                        <li>
                            <a href="" class="block py-2 pl-16 hover:bg-primary-red/10">My Profile</a>
                        </li>
                        <li>
                            <a href="" class="block py-2 pl-16 hover:bg-primary-red/10">Edit Profile</a>
                        </li>
                        <li>
                            <a href="" class="block py-2 pl-16 hover:bg-primary-red/10">Change Username</a>
                        </li>
                        <li>
                            <a href="" class="block py-2 pl-16 hover:bg-primary-red/10">Change Password</a>
                        </li>
                    </x-slot>
                </x-sidebar-menu>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('parents*')">
                    <x-slot name="icon">
                        <x-heroicon-o-users class="w-5 h-5" />
                    </x-slot>
                    Parents
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('children') }}" :active="request()->is('children')">
                    <x-slot name="icon">
                        <x-heroicon-o-academic-cap class="w-5 h-5" />
                    </x-slot>
                    Children
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-location-marker class="w-5 h-5" />
                    </x-slot>
                    Address
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-archive class="w-5 h-5" />
                    </x-slot>
                    Legacy
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-hand class="w-5 h-5" />
                    </x-slot>
                    Healthcare
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-identification class="w-5 h-5" />
                    </x-slot>
                    Emergency Contact
                </x-sidebar-item>
            </ul>
        </div>

        <div class="py-8">
            <ul>
                <x-sidebar-menu  :active="request()->is('profile*')">
                    <x-slot name="icon">
                        <x-heroicon-o-presentation-chart-line class="w-5 h-5" />
                    </x-slot>
                    Activities
                    <x-slot name="menu">
                        <li>
                            <a href="" class="block py-2 pl-16 hover:bg-primary-red/10">My Profile</a>
                        </li>
                        <li>
                            <a href="" class="block py-2 pl-16 hover:bg-primary-red/10">Edit Profile</a>
                        </li>
                        <li>
                            <a href="" class="block py-2 pl-16 hover:bg-primary-red/10">Change Username</a>
                        </li>
                        <li>
                            <a href="" class="block py-2 pl-16 hover:bg-primary-red/10">Change Password</a>
                        </li>
                    </x-slot>
                </x-sidebar-menu>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-question-mark-circle class="w-5 h-5" />
                    </x-slot>
                    Contact Us
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-collection class="w-5 h-5" />
                    </x-slot>
                    Parent Directory
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-calendar class="w-5 h-5" />
                    </x-slot>
                    Alumni Events
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
                <x-sidebar-menu  :active="request()->is('profile*')">
                    <x-slot name="icon">
                        <x-heroicon-o-user class="w-5 h-5" />
                    </x-slot>
                    Profile
                    <x-slot name="menu">
                        <li>
                            <a href="" class="block py-2 pl-16 hover:bg-primary-red/10">My Profile</a>
                        </li>
                        <li>
                            <a href="" class="block py-2 pl-16 hover:bg-primary-red/10">Edit Profile</a>
                        </li>
                        <li>
                            <a href="" class="block py-2 pl-16 hover:bg-primary-red/10">Change Username</a>
                        </li>
                        <li>
                            <a href="" class="block py-2 pl-16 hover:bg-primary-red/10">Change Password</a>
                        </li>
                    </x-slot>
                </x-sidebar-menu>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('parents*')">
                    <x-slot name="icon">
                        <x-heroicon-o-users class="w-5 h-5" />
                    </x-slot>
                    Parents
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-academic-cap class="w-5 h-5" />
                    </x-slot>
                    Children
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-location-marker class="w-5 h-5" />
                    </x-slot>
                    Address
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-archive class="w-5 h-5" />
                    </x-slot>
                    Legacy
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-hand class="w-5 h-5" />
                    </x-slot>
                    Healthcare
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-identification class="w-5 h-5" />
                    </x-slot>
                    Emergency Contact
                </x-sidebar-item>
            </ul>
        </div>

        <div class="py-8">
            <ul>
                <x-sidebar-menu  :active="request()->is('profile*')">
                    <x-slot name="icon">
                        <x-heroicon-o-presentation-chart-line class="w-5 h-5" />
                    </x-slot>
                    Activities
                    <x-slot name="menu">
                        <li>
                            <a href="" class="block py-2 pl-16 hover:bg-primary-red/10">My Profile</a>
                        </li>
                        <li>
                            <a href="" class="block py-2 pl-16 hover:bg-primary-red/10">Edit Profile</a>
                        </li>
                        <li>
                            <a href="" class="block py-2 pl-16 hover:bg-primary-red/10">Change Username</a>
                        </li>
                        <li>
                            <a href="" class="block py-2 pl-16 hover:bg-primary-red/10">Change Password</a>
                        </li>
                    </x-slot>
                </x-sidebar-menu>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-question-mark-circle class="w-5 h-5" />
                    </x-slot>
                    Contact Us
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-collection class="w-5 h-5" />
                    </x-slot>
                    Parent Directory
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-calendar class="w-5 h-5" />
                    </x-slot>
                    Alumni Events
                </x-sidebar-item>
            </ul>
        </div>
        
    </div>
</section>