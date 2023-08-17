<div>
    <h2 class="text-2xl font-semibold font-heading text-primary-blue">
        Accommodation Documents
    </h2>

    <div class="mt-8">
        {{ $this->table }}
    </div>


    <div x-data="{ enable: $wire.entangle('enable_form') }" 
        class="pt-8 pb-32 border-t"
        x-show="enable"
        x-cloak>
        <form wire:submit.prevent="save" class="p-8 bg-gray-100 border rounded-md" novalidate>
            
            {{ $this->form }}

            <div class="flex justify-end gap-8 mt-8">
                <button type="button" wire:click="cancel()" class="btn-primary">Cancel</button>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>

</div>
