<div>
    <h2 class="text-2xl font-semibold font-heading text-primary-blue">
        Supplemental Recommendation
    </h2>

    <p class="mt-8">
        The ability to send a request for your optional Supplemental Recommendation will appear once an application has been submitted.
    </p>
    <div class="mt-8">
        {{ $this->table }}
    </div>


    <p class="mt-8"><strong>DUE DATE</strong>: St. Ignatius College Preparatory must receive all supplemental recommendations by the end of the day on Wednesday, January 10, 2024 at 11:59 PT.</p>

    <div x-data="{ open: $wire.entangle('enable_form') }" 
        class="pb-8"
        x-show="open" x-cloak>
    </div>
    

    <div x-data="{ enable: $wire.entangle('enable_form') }" 
            class="pt-8 pb-32 border-t"
            x-show="enable"
            x-cloak>
        <form wire:submit.prevent="save" class="p-8 bg-gray-100 border rounded-md " novalidate>

            
            
            <p class="mb-8 ">
                All required fields are color <span class="font-bold color-red-800">red</span> and have an asterisk (<span class="font-bold color-red-800">*</span>).
            </p>
            
            {{ $this->form }}

            <div class="flex justify-end gap-8 mt-8">
                <button type="button" wire:click="cancel()" class="btn-primary">Cancel</button>
                <button type="submit" class="btn-primary">Submit</button>
            </div>
        </form>
    </div>

</div>
