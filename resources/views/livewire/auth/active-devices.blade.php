<div>
    @if(!request()->is('devices'))
    <x-modal name="show-faq" :show="true"  maxWidth="4xl">
        <div class="p-10 bg-white border rounded-lg shadow-lg">

            <div class="flex gap-4">
                <x-heroicon-o-exclamation class="flex-shrink-0 w-20 h-20 text-primary-red"/>
                <div>
                    <h2 class="text-2xl font-bold text-primary-red">Multiple Devices Detected</h2>
                    <p class="mt-2 mb-4">
                        We are strictly implementing a policy of one active device per account. Please log out from other devices to continue.
                    </p>
                    <a href="{{ url('devices') }}" class="text-link">View Devices</a>
                </div>
            </div>
        </div>
    </x-modal>
    @endif
</div>