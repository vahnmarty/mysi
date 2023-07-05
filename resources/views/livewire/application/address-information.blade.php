<div>
    <h2 class="text-2xl font-semibold font-heading text-primary-blue">
        Address Information
    </h2>

    <div class="mt-8">
        {{ $this->table }}
    </div>

    <div x-data="{ enable: $wire.entangle('enable_form') }" 
        x-show="enable" 
        x-cloak 
        class="pt-8 pb-32 mt-8 border-t">
        <form wire:submit.prevent="save" class="p-8 bg-gray-100 border rounded-md " novalidate>

            {{ $this->form }}

            <div class="flex justify-end mt-8">
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
