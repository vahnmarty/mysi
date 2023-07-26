<div>
    <h2 class="text-2xl font-semibold font-heading text-primary-blue">
        Parent/Guardian Information
    </h2>

    <div class="max-w-full mt-8 overflow-x-auto">
        {{ $this->table }}
    </div>

    <div class="py-6">
        <button x-data="{ form: $wire.entangle('enable_form') }" 
            x-show="!form" 
            class="btn-primary-red" wire:click="add">Add</button>
    </div>


    <div x-data="{ enable: $wire.entangle('enable_form') }" 
            class="invisible pt-8 pb-32 border-t"
            :class="{ 'visible' : enable, 'invisible' : !enable }">
        <form wire:submit.prevent="save" class="p-8 bg-gray-100 border rounded-md " novalidate>

            {{ $this->form }}

            <div class="flex justify-end gap-8 mt-8">
                <button type="button" wire:click="cancel()" class="btn-primary">Cancel</button>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
