<div>
    <div class="flex justify-between">
        <h2 class="text-2xl font-semibold font-heading text-primary-blue">
            Application for {{ $app->student->getFullName() }}
        </h2>
    </div>


    <div class="pb-32 mt-8">

        @if($is_submitted)
        <div class="p-8 bg-green-100 border border-green-300 rounded-lg">

            <div class="flex justify-center">
                <x-heroicon-s-check-circle class="w-40 h-40 text-green-500"/>
            </div>
            <div class="mt-8 text-center">
                <h1 class="text-2xl font-bold font-heading">Application Submitted Successfully!</h1>
                <a href="{{ url('admission') }}" class="mt-8 btn-primary">Back to Applications</a>
            </div>
        </div>
        @else
        <form wire:submit.prevent="submit">

            {{ $this->form }}

            <div x-data="{ open: $wire.entangle('is_validated') }" 
                x-show="open"
                x-cloak
                class="flex justify-center mt-8">
                <button type="submit" x-on:click="$dispatch('page-loading-open')" class="btn-primary">Pay Fee and Submit Application</button>
            </div>
        </form>
        @endif
    </div>
</div>
