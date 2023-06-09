<div>
    <div class="flex justify-between">
        <h2 class="text-2xl font-semibold font-heading text-primary-blue">
            Application for {{ $app->child->getFullName() }}
        </h2>
    </div>


    <div class="pb-32 mt-8">
        <form wire:submit.prevent="save">

            {{ $this->form }}

            <div class="flex justify-end mt-8">
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
