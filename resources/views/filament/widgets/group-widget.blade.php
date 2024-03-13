@props([
    'columns' => '3',
])

@php
    $columns = (int) $this->getColumns();
@endphp


<x-filament::widget class="filament-stats-overview-widget">
    @if ($this->canViewWidget())
        <h2 class="mb-8 text-xl font-semibold">{{ $title }}</h2>
        <div {!! ($pollingInterval = $this->getPollingInterval()) ? "wire:poll.{$pollingInterval}" : '' !!}>

            <div
                {{ $attributes->class([
                    'filament-stats grid gap-4 lg:gap-8',
                    'md:grid-cols-3' => $columns === 3,
                    'md:grid-cols-1' => $columns === 1,
                    'md:grid-cols-2' => $columns === 2,
                    'md:grid-cols-2 xl:grid-cols-4' => $columns === 4,
                    'md:grid-cols-2 xl:grid-cols-5' => $columns === 5,
                ]) }}>
                @foreach ($this->getCachedCards() as $card)
                    {{ $card }}
                @endforeach
            </div>

        </div>
    @endif
</x-filament::widget>
