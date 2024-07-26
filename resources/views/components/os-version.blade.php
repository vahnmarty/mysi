<div class="py-6">
    @php 

    $agent = new Jenssegers\Agent\Agent();

    @endphp

    @if($agent->isDesktop())

        @if(isWindowsOS())

            <div class="px-4 py-4 border border-blue-300 rounded-md shadow-md md:px-8 bg-blue-50">
                <div class="flex items-start gap-4">
                    
                    <x-heroicon-o-desktop-computer class="flex-shrink-0 w-10 h-10 text-gray-700 "/>
        
                    <div>
                        For the best user experience on Windows PC, please update your operating system to Windows 10 or higher (Windows 11).
                        <a href="{{ route('device-compatability') }}" class="text-link">Learn More</a>.
                        <span>Our system detected <strong>{{ getUserOS() }}</strong>, if this is incorrect, please disregard this message.</span>
                    </div>
                      
                </div>
            </div>

        @endif

        @if(isMacOS())

            <div class="px-4 py-4 border border-blue-300 rounded-md shadow-md md:px-8 bg-blue-50">
                <div class="flex items-start gap-4">
                    
                    <x-heroicon-o-desktop-computer class="flex-shrink-0 w-10 h-10 text-gray-700 "/>
        
                    <div>
                        For the best user experience on Mac, please update your operating system to Monterey or higher (Ventura/Sonoma).
                        <a href="{{ route('device-compatability') }}" class="text-link">Learn More</a>.
                        <span>Our system detected <strong>{{ getUserOS() }}</strong>, if this is incorrect, please disregard this message.</span>
                    </div>
                      
                </div>
            </div>
        @endif
    
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