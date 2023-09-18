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

        <section class="bg-white border border-gray-300 rounded-lg">
            <header class="p-6 bg-gray-100 rounded-lg">
                <h1 class="text-xl font-bold">Applicant Information</h1>
            </header>
            <div class="p-6">
                
            </div>
        </section>

    </div>
    @php
        $relationManagers = $this->getRelationManagers();
    @endphp

    @if ((! $this->hasCombinedRelationManagerTabsWithForm()) || (! count($relationManagers)))
        {{ $this->form }}
    @endif

    @if (count($relationManagers))
        @if (! $this->hasCombinedRelationManagerTabsWithForm())
            <x-filament::hr />
        @endif

        <x-filament::resources.relation-managers
            :active-manager="$activeRelationManager"
            :form-tab-label="$this->getFormTabLabel()"
            :managers="$relationManagers"
            :owner-record="$record"
            :page-class="static::class"
        >
            @if ($this->hasCombinedRelationManagerTabsWithForm())
                <x-slot name="form">
                    {{ $this->form }}
                </x-slot>
            @endif
        </x-filament::resources.relation-managers>
    @endif
</x-filament::page>
