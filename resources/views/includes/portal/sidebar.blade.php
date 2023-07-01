<section>
    <div class="hidden lg:flex flex-col  w-64 h-full min-h-[90vh] bg-gray-100 border-r">

        <div class="py-8">
            <ul>
                <x-sidebar-item href="{{ route('dashboard') }}" :active="request()->is('dashboard')">
                    <x-slot name="icon">
                        <x-heroicon-o-home class="w-4 h-4" />
                    </x-slot>
                    Home
                </x-sidebar-item>
                <!-- <x-sidebar-menu  :active="request()->is('profile*')">
                    <x-slot name="icon">
                        <x-heroicon-o-user class="w-4 h-4" />
                    </x-slot>
                    Profile
                    <x-slot name="menu">
                        <li>
                            <a href="{{ url('profile') }}" class="sub-menu">My Profile</a>
                        </li>
                        <li>
                            <a href="{{ url('profile/edit') }}" class="sub-menu">Edit Profile</a>
                        </li>
                    </x-slot>
                </x-sidebar-menu> -->
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('parents*')">
                    <x-slot name="icon">
                        <x-heroicon-o-users class="w-4 h-4" />
                    </x-slot>
                    Parents
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('children') }}" :active="request()->is('children*')">
                    <x-slot name="icon">
                        <x-heroicon-o-academic-cap class="w-4 h-4" />
                    </x-slot>
                    Children
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('address') }}" :active="request()->is('address*')">
                    <x-slot name="icon">
                        <x-heroicon-o-location-marker class="w-4 h-4" />
                    </x-slot>
                    Address
                </x-sidebar-item>
                @if(Auth::user()->account?->applications()->count())
                <x-sidebar-item href="{{ url('legacy') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-archive class="w-4 h-4" />
                    </x-slot>
                    Legacy
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('healthcare') }}" :active="request()->is('healthcare')">
                    <x-slot name="icon">
                        <x-heroicon-o-hand class="w-4 h-4" />
                    </x-slot>
                    Healthcare
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('emergency-contact') }}" :active="request()->is('emergency-contact')">
                    <x-slot name="icon">
                        <x-heroicon-o-identification class="w-4 h-4" />
                    </x-slot>
                    Emergency Contact
                </x-sidebar-item>
                @endif
            </ul>
        </div>

        <div class="py-8">
            <ul>
                <x-sidebar-menu  :active="request()->is('activities*')">
                    <x-slot name="icon">
                        <x-heroicon-o-presentation-chart-line class="w-4 h-4" />
                    </x-slot>
                    Activities
                    <x-slot name="menu">
                        <li>
                            <a href="" class="sub-menu">Eight Graders</a>
                            
                            <ul class="pl-4 list-[circle]">
                                <li>
                                    <a href="{{ url('book-a-wildcat-experience') }}" class="sub-menu">Book a Wildcat Experience</a>
                                </li>
                                <li>
                                    <a href="{{ url('admission') }}" class="sub-menu">Apply at SI</a>
                                </li>
                                <li>
                                    <a href="{{ url('supplemental-recommendation') }}" class="sub-menu">Supplemental Recommendation</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="" class="sub-menu">Existing Students</a>
                        </li>
                        <li>
                            <a href="" class="sub-menu">Transfer Students</a>
                        </li>
                        <li>
                            <a href="" class="sub-menu">Summer Camp</a>
                        </li>
                        <li>
                            <a href="" class="sub-menu">Summer School</a>
                        </li>
                        <li>
                            <a href="" class="sub-menu">Buy SI Merch</a>
                        </li>
                        <li>
                            <a href="" class="sub-menu">SI Atheltics</a>
                        </li>
                        <li>
                            <a href="" class="sub-menu">SI Arts</a>
                        </li>
                        <li>
                            <a href="" class="sub-menu">SI Newsletter</a>
                        </li>
                        <li>
                            <a href="" class="sub-menu">Donate to SI</a>
                        </li>
                    </x-slot>
                </x-sidebar-menu>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-question-mark-circle class="w-4 h-4" />
                    </x-slot>
                    Contact Us
                </x-sidebar-item>
                <!-- <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-collection class="w-4 h-4" />
                    </x-slot>
                    Parent Directory
                </x-sidebar-item> -->
                <!-- <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-calendar class="w-4 h-4" />
                    </x-slot>
                    Alumni Events
                </x-sidebar-item> -->
            </ul>
        </div>
    
    </div>
    <div x-data="{ open: false }" 
        x-on:open-menu.window="open = !open"
        x-transition
        :class="{ '-translate-x-[100vw]' : !open, 'translate-x-0' : open }"
        class="z-50 fixed inset-0 top-16 w-full min-h-screen -translate-x-[100vw] transition-all duration-300 ease-in-out bg-white lg:hidden">

        <div class="py-8">
            <ul>
                <x-sidebar-item href="{{ route('dashboard') }}" :active="request()->is('dashboard')">
                    <x-slot name="icon">
                        <x-heroicon-o-home class="w-4 h-4" />
                    </x-slot>
                    Home
                </x-sidebar-item>
                <!-- <x-sidebar-menu  :active="request()->is('profile*')">
                    <x-slot name="icon">
                        <x-heroicon-o-user class="w-4 h-4" />
                    </x-slot>
                    Profile
                    <x-slot name="menu">
                        <li>
                            <a href="{{ url('profile') }}" class="sub-menu">My Profile</a>
                        </li>
                        <li>
                            <a href="{{ url('profile/edit') }}" class="sub-menu">Edit Profile</a>
                        </li>
                    </x-slot>
                </x-sidebar-menu> -->
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('parents*')">
                    <x-slot name="icon">
                        <x-heroicon-o-users class="w-4 h-4" />
                    </x-slot>
                    Parents
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('children') }}" :active="request()->is('children*')">
                    <x-slot name="icon">
                        <x-heroicon-o-academic-cap class="w-4 h-4" />
                    </x-slot>
                    Children
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('address') }}" :active="request()->is('address*')">
                    <x-slot name="icon">
                        <x-heroicon-o-location-marker class="w-4 h-4" />
                    </x-slot>
                    Address
                </x-sidebar-item>
                @if(Auth::user()->account?->applications()->count())
                <x-sidebar-item href="{{ url('legacy') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-archive class="w-4 h-4" />
                    </x-slot>
                    Legacy
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('healthcare') }}" :active="request()->is('healthcare')">
                    <x-slot name="icon">
                        <x-heroicon-o-hand class="w-4 h-4" />
                    </x-slot>
                    Healthcare
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('emergency-contact') }}" :active="request()->is('emergency-contact')">
                    <x-slot name="icon">
                        <x-heroicon-o-identification class="w-4 h-4" />
                    </x-slot>
                    Emergency Contact
                </x-sidebar-item>
                @endif
            </ul>
        </div>

        <div class="py-8">
            <ul>
                <x-sidebar-menu  :active="request()->is('activities*')">
                    <x-slot name="icon">
                        <x-heroicon-o-presentation-chart-line class="w-4 h-4" />
                    </x-slot>
                    Activities
                    <x-slot name="menu">
                        <li>
                            <a href="" class="sub-menu">Eight Graders</a>
                            
                            <ul class="pl-4 list-[circle]">
                                <li>
                                    <a href="{{ url('book-a-wildcat-experience') }}" class="sub-menu">Book a Wildcat Experience</a>
                                </li>
                                <li>
                                    <a href="{{ url('admission') }}" class="sub-menu">Apply at SI</a>
                                </li>
                                <li>
                                    <a href="{{ url('supplemental-recommendation') }}" class="sub-menu">Supplemental Recommendation</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="" class="sub-menu">Existing Students</a>
                        </li>
                        <li>
                            <a href="" class="sub-menu">Transfer Students</a>
                        </li>
                        <li>
                            <a href="" class="sub-menu">Summer Camp</a>
                        </li>
                        <li>
                            <a href="" class="sub-menu">Summer School</a>
                        </li>
                        <li>
                            <a href="" class="sub-menu">Buy SI Merch</a>
                        </li>
                        <li>
                            <a href="" class="sub-menu">SI Atheltics</a>
                        </li>
                        <li>
                            <a href="" class="sub-menu">SI Arts</a>
                        </li>
                        <li>
                            <a href="" class="sub-menu">SI Newsletter</a>
                        </li>
                        <li>
                            <a href="" class="sub-menu">Donate to SI</a>
                        </li>
                    </x-slot>
                </x-sidebar-menu>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-question-mark-circle class="w-4 h-4" />
                    </x-slot>
                    Contact Us
                </x-sidebar-item>
                <!-- <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-collection class="w-4 h-4" />
                    </x-slot>
                    Parent Directory
                </x-sidebar-item>
                <x-sidebar-item href="{{ url('parents') }}" :active="request()->is('test')">
                    <x-slot name="icon">
                        <x-heroicon-o-calendar class="w-4 h-4" />
                    </x-slot>
                    Alumni Events
                </x-sidebar-item> -->
            </ul>
        </div>
        
    </div>
</section>