<x-filament::widget class="filament-stats-overview-widget">

    <h2 class="mb-8 text-xl font-semibold">{{ $title }}</h2>
    <div
        {!! ($pollingInterval = $this->getPollingInterval()) ? "wire:poll.{$pollingInterval}" : '' !!}
    >
        <x-filament::stats :columns="$this->getColumns()">
            @foreach ($this->getCachedCards() as $card)
                {{ $card }}
            @endforeach
        </x-filament::stats>
    </div>
</x-filament::widget>
