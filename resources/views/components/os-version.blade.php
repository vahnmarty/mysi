<div class="py-6">
    @php 

    $agent = new Jenssegers\Agent\Agent();

    @endphp

    @if($agent->isDesktop())
    <div class="px-4 py-4 border border-blue-300 rounded-md shadow-md md:px-8 bg-blue-50">
        <div class="flex items-start gap-4">
            
            <x-heroicon-o-desktop-computer class="flex-shrink-0 w-10 h-10 text-gray-700 "/>

            <div>
                For the best user experience on [PC/Mac], please update your operating system to [Windows 10/Monterey] or higher ([Windows 11/Ventura/Sonoma]).
                <a href="{{ route('device-compatability') }}" class="text-link">Learn More</a>
            </div>
              
        </div>
    </div>
    @elseif($agent->isMobile())
    <div class="px-4 py-4 border border-blue-300 rounded-md shadow-md md:px-8 bg-blue-50">
        <div class="flex items-start gap-4">
            <x-heroicon-o-device-mobile class="flex-shrink-0 w-10 h-10 text-gray-700 "/>

            <div>
                This application cannot be filled out using a mobile device. Please use a computer using Windows or Mac.
                <a href="{{ route('device-compatability') }}" class="text-link">Learn More</a>
            </div>
        </div>
    </div>
    @elseif($agent->is('Linux'))
    <div class="px-4 py-4 border border-blue-300 rounded-md shadow-md md:px-8 bg-blue-50">
        <div class="flex items-start gap-4">
            
            <x-heroicon-o-terminal class="flex-shrink-0 w-10 h-10 text-gray-700 "/>
              
            <div>
                <p>
                    This application cannot be filled out using [Linux/Unix]. Please use a computer using Windows or Mac.
                    <a href="{{ route('device-compatability') }}" class="text-link">Learn More</a>
                </p>
            </div>
        </div>
    </div>
    @endif
</div>