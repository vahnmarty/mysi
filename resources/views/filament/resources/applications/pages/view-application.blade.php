<x-filament::page
    :widget-data="['record' => $record]"
    :class="
        \Illuminate\Support\Arr::toCssClasses([
            'filament-resources-view-record-page',
            'filament-resources-' . str_replace('/', '-', $this->getResource()::getSlug()),
            'filament-resources-record-' . $record->getKey(),
        ])
    "
>
    <div>

        {{ $this->form }}

    </div>
</x-filament::page>
