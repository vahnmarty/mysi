<div>
    <h2 class="text-2xl font-semibold font-heading text-primary-blue">
        Pay Application Fee
    </h2>

    @if($app_uuid)

    <div class="mt-8">

        @if($paid)
        <div class="flex justify-center">
            <img src="{{ asset('img/mail.svg') }}" class="w-40 h-40"/>
        </div>
        <div class="mt-8 text-center">
            <h1 class="text-5xl font-bold font-heading">Application <span class="text-primary-blue">Paid</span></h1>
            <p class="mt-6">If you have any questions regarding the Admission process, please visit our website at <a href="https://www.siprep.org/admissions" class="text-link">https://www.siprep.org/admissions</a> or email us at <a href="mailto:admissions@siprep.org" class="text-link">admissions@siprep.org</a>.</p>
            <a href="{{ url('admission') }}" class="mt-8 btn-primary">Back to Applications</a>
        </div>
        @else
        <form wire:submit.prevent="checkout" class="p-8 border rounded-md " novalidate>
            
            {{ $this->form }}

            <div class="flex justify-center gap-8 mt-8">
                <button type="submit" class="btn-primary">
                    <span class="mr-4 animate-spin" wire:loading wire:target="checkout">
                        <x-heroicon-o-dots-circle-horizontal class="w-4 h-4" />
                    </span>
                    <span>Pay Application Fee</span>
                </button>
            </div>
        </form>
        @endif
    </div>

    @endif

</div>
