<header class="relative">
    <div class="py-6 bg-primary-red">
        <div class="px-8 mx-auto">
            <div class="flex transition-all duration-300 ease-in-out hover:opacity-80">
                <a href="{{ url('/') }}" class="block">
                    <img src="{{ asset('img/logo-white.png') }}" class="flex-shrink-0 w-auto h-16" alt="">
                </a>
                <div class="pl-8">
                    <a href="{{ url('/') }}">
                        <h4 class="text-2xl font-extrabold text-white md:text-4xl font-heading">MySI Portal</h4>
                        <p class="text-white lg:text-xl font-heading">St. Ignatius College Preparatory</p>
                    </a>
                    <p class="text-xl text-white font-heading"></p>
                </div>
            </div>
        </div>
    </div>
    @if(null)
    <img src="{{ asset('img/headerWave.png') }}" class="hidden w-full h-10"/>
    @endif
</header>