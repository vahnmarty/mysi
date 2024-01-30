<div x-data="{ form: true }">
    <div>
    
        <div class="flex justify-between">
            <h2 class="text-2xl font-semibold font-heading text-primary-blue">
                Registration
            </h2>
        </div>


    
        <div x-data="{ done: $wire.entangle('is_submitted') }"  class="pb-32 mt-8">
    
            <div x-show="done" x-cloak class="p-8 rounded-lg">
    
                <div class="flex justify-center">
                    <img src="{{ asset('img/mail.svg') }}" class="w-40 h-40"/>
                </div>
                <div class="mt-8 text-center">
                    <h1 class="text-5xl font-bold font-heading">Thank <span class="text-primary-blue">You</span></h1>
                    <p class="mt-6">Thank you for submitting your application to St. Ignatius College Preparatory. If you have any questions regarding the Admission process, please visit our website at <a href="https://www.siprep.org/admissions" class="text-link hover:underline">https://www.siprep.org/admissions</a> or email us at <a href="mailto:admissions@siprep.org" class="text-link hover:underline">admissions@siprep.org</a>.</p>
                    <a href="{{ url('admission') }}" class="mt-8 btn-primary">Back to Applications</a>
                </div>
            </div>

            
            <div x-show="!done">
                <form wire:submit.prevent="submit" novalidate>
    
                    {{ $this->form }}
        
                    <div class="flex justify-center mt-8">
                        <button type="submit" class="btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>


