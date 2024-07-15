<div>
    <div class="flex justify-between">
        <div>
            <h2 class="text-2xl font-semibold font-heading text-primary-blue">
                My Devices
            </h2>
            <p class="mt-3">
                See a list of all devices currently logged into your account, Only one active session is allowed per user to enhance data integrity.
            </p>
        </div>
    </div>

    <div class="pb-32 mt-8">
        <div class="grid xl:grid-cols-2">
            <div class="space-y-4">
                @foreach($sessions as $sesh)
                <div class="flex gap-3 pb-3 border-b border-gray-100">
                    <x-heroicon-o-desktop-computer class="w-8 h-8 text-gray-600"/>
                    <div class="text-gray-600 md:w-96">
                        <p>{{ $sesh->getOs() }} - {{ $sesh->getBrowser() }}</p>
                        <p class="text-sm">{{ $sesh->ip_address }} 
                            @if($sesh->id == session()->getId())
                            <strong class="ml-2 text-green-600">This device</strong>
                            @else
                            <span class="ml-2 text-xs">Last active {{ Carbon\Carbon::createFromTimestamp($sesh->last_activity)->diffForHumans() }}</span>
                            @endif
                        </p>
                    </div>
    
                    @if($sesh->id != session()->getId())
                    <div class="ml-4">
                        <button x-data
                        x-on:click="if(confirm('Are you sure you want to logout this device?')){
                            $wire.logout(`{{ $sesh->id }}`);
                        }"
                        type="button"
                        class="inline-flex items-center px-4 py-1.5 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out border border-transparent rounded-md bg-primary-blue dark:bg-gray-200 dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                            Logout
                        </button>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @if(count($sessions) > 1)
        <div class="mt-8">
            <button x-data x-on:click="confirm('Are you sure you want to logout other devices?')" type="button" class="btn-danger">Logout outher browser sessions?</button>
        </div>
        @endif
    </div>
</div>
