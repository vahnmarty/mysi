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

    <div class="mt-8">
        <div class="space-y-4">
            @foreach($sessions as $sesh)
            <div class="flex gap-3">
                <x-heroicon-o-desktop-computer class="w-8 h-8 text-gray-600"/>
                <div class="text-gray-600">
                    <p>{{ $sesh->getOs() }} - {{ $sesh->getBrowser() }}</p>
                    <p class="text-sm">{{ $sesh->ip_address }} 
                        @if($sesh->id == session()->getId())
                        <strong class="ml-2 text-green-600">This device</strong>
                        @else
                        <span class="ml-2">Last active {{ Carbon\Carbon::createFromTimestamp($sesh->last_activity)->diffForHumans() }}</span>
                        @endif
                    </p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-8">
            <button x-data x-on:click="confirm('Are you sure you want to logout other devices?')" type="button" class="btn-danger">Logout outher browser sessions?</button>
        </div>
    </div>
</div>
