<div>
    <div class="flex justify-between">
        <h2 class="text-2xl font-semibold font-heading text-primary-blue">
            Application for {{ $app->student->getFullName() }}
        </h2>
    </div>


    <div class="pb-32 mt-8">
        <form wire:submit.prevent="submit">

            {{ $this->form }}

            @if($is_validated)
            <div class="flex justify-center mt-8">
                <button type="submit" class="btn-primary">Pay Fee and Submit Application</button>
            </div>
            @endif
        </form>
    </div>
</div>
