<div class="fixed bottom-0 left-0 right-0 py-1 bg-gray-300">
    <div class="px-8 mx-auto max-w-7xl">

        <div class="grid items-center grid-cols-3 gap-8">
            <div>
                @livewire('status.pinger')
            </div>

            <div class="flex-1">
                <div wire:loading class="flex justify-center">
                    <div class="flex items-center text-xs">
                        <svg class="w-5 h-5 mr-3 -ml-1 text-primary-red animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                          </svg>
                        <span>Loading... Please wait...</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <div class="flex gap-1 text-xs">
                    <x-heroicon-o-calendar class="w-4 h-4"/>
                    <span>{{ date('F j Y') }}</span>
                </div>
            </div>
        </div>
        
    </div>
</div>
