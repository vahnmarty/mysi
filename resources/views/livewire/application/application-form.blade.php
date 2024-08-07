<div>
    <div class="flex justify-between">
        <h2 class="text-2xl font-semibold font-heading text-primary-blue">
            Application for {{ $app->student->getFullName() }}
        </h2>
    </div>

    <x-os-version/>


    @php
    $agent = new Jenssegers\Agent\Agent();
    @endphp
    

    @if(!$agent->isMobile() && !$agent->is('Linux'))
    <div wire:loading wire:target="submit"
        class="fixed inset-0 bg-gray-900/90 z-[1000] flex items-center justify-center lg:p-32 p-8">
        <div 
            x-on:click.away="open = false"
            class="flex items-center justify-center w-full h-full bg-white/70">
            
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mr-8 text-primary-red animate-bounce">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
            </svg>

            
            <h3 class="text-3xl font-bold text-center font-heading text-primary-blue">Processing ...</h3>

        </div>
    </div>

    <div x-data="{ done: $wire.entangle('is_submitted') }"  class="pb-32 mt-8">

        <div x-show="done" x-cloak class="p-8 rounded-lg">

            <div class="flex justify-center">
                <img src="{{ asset('img/mail.svg') }}" class="w-40 h-40"/>
            </div>
            <div class="mt-8 text-center">
                <h1 class="text-5xl font-bold font-heading">Thank <span class="text-primary-blue">You</span></h1>
                <p class="mt-6">Thank you for submitting your application to St. Ignatius College Preparatory. If you have any questions regarding the Admission process, please visit our website at <a href="https://www.siprep.org/admissions" class="text-link">https://www.siprep.org/admissions</a> or email us at <a href="mailto:admissions@siprep.org" class="text-link">admissions@siprep.org</a>.</p>

                @if($type == 'transfer')
                <a href="{{ url('transfer-applications') }}" class="mt-8 btn-primary">Back to Transfer Applications</a>
                @else
                <a href="{{ url('admission') }}" class="mt-8 btn-primary">Back to Applications</a>
                @endif
            </div>
        </div>
        <div x-show="!done">
            <form wire:submit.prevent="submit" novalidate>

                {{ $this->form }}
    
                <div x-data="{ open: $wire.entangle('is_validated'), amount : $wire.entangle('amount') }" 
                    x-show="open"
                    x-cloak
                    class="flex justify-center mt-8">
                    <button type="button" wire:click="submit" class="btn-primary" x-text="amount > 0 ? 'Pay Fee and Submit Application' : 'Submit Application'"></button>
                </div>
            </form>
        </div>
        
    </div>
    @endif
</div>
