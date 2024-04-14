<div>
    <div>
    
        <div class="flex justify-between">
            <h2 class="text-2xl font-semibold font-heading text-primary-blue">
                Re-Registration
            </h2>
        </div>


    
        <div  class="pb-32 mt-8">
            <div x-data="{ valid: $wire.entangle('valid')}">
                
                <form x-show="valid" wire:submit.prevent="submit" novalidate>
    
                    {{ $this->form }}
        
                    <div class="flex justify-center mt-8">
                        <button type="submit" class="btn-primary">Submit</button>
                    </div>
                </form>
                
                <div x-show="!valid" class="max-w-lg mx-auto text-center">
                    <img src="{{ asset('img/error.jpg') }}" class="w-64 h-auto mx-auto">
                
                    <h3 class="mt-8 text-2xl font-bold">Student Not Eligible.</h3>
                    <p class="mt-4 text-sm text-gray-700">
                        We regret to inform you that this student is not eligible for re-registration.
                    </p>
                </div>
            </div>
            
        </div>
    </div>
</div>


