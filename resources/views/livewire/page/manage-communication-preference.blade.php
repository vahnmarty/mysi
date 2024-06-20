<div>
    <h2 class="text-2xl font-semibold font-heading text-primary-blue">
        Manage Communication Preference
    </h2>

    <section>
        <div class="mt-8">
            <form wire:submit.prevent="update">
                {{ $this->form }}

                <div class="flex mt-8">
                    <button type="submit" class="btn-primary">Update</button>
                </div>
            </form>
        </div>
    </section>


</div>
