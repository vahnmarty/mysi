<div>
    <div class="flex justify-between">
        <h2 class="text-2xl font-semibold font-heading text-primary-blue">
            Application for {{ $app->student->getFullName() }}
        </h2>
    </div>

    <div class="pb-32 mt-8">
        <div>
            {{ $this->form }}
        </div>
    </div>
</div>
