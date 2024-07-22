@if($app->appStatus?->claver_award)

@if($app->appStatus->claver_award_acknowledged_at)
<div class="col-span-2 p-3 bg-green-200 border border-red-100 border-dashed rounded-md shadow-md">
    <div class="flex gap-3">
        <x-heroicon-o-check-circle class="w-10 h-10 text-green-500 shadow-sm"/>
        <div>
            <p class="text-base font-bold">Claver Award Letter</p>
            <div class="flex items-center gap-4">
                <a href="#" x-data="{}"
                x-on:click.prevent="$dispatch('open-modal', 'show-claver-award')" 
                class="underline">View</a>
            </div>
        </div>
    </div>
</div>
@else
<div class="col-span-2 p-3 bg-red-100 border border-red-100 border-dashed rounded-md shadow-md">
    <div class="flex gap-3">
        <x-heroicon-o-mail class="w-10 h-10 shadow-sm text-primary-red"/>
        <div>
            <p class="text-base font-bold">Claver Award Letter</p>
            <div class="flex items-center gap-4">
                <a href="#" x-data="{}"
                x-on:click.prevent="$dispatch('open-modal', 'show-claver-award')" 
                class="underline">View</a>
            </div>
        </div>
    </div>
</div>
@endif

<x-modal name="show-claver-award" :show="false"  maxWidth="4xl">
    <div class="p-10 bg-white border rounded-lg shadow-lg">

        <header class="flex gap-6">
            <img src="{{ asset('img/logo.png') }}" alt="">
            <div>
                <p>
                    St. Ignatius College Preparatory<br>
                    2001 37th Avenue<br>
                    San Francisco, California 94116<br>
                    (415) 731-7500<br>
                </p>
                <p class="mt-6"> Business Office</p>
            </div>
        </header>

        <article class="mt-16 html-content">
            {!! $claver_award_content !!}
        </article>

        <div class="flex gap-6 pt-8 mt-8 border-t border-dashed border-primary-red">
            
            @if($app->appStatus->claver_award_acknowledged_at)
            <p>Acknowledged last {{ date('F d, Y', strtotime($app->appStatus->claver_award_acknowledged_at)) }}.</p>
            @else
            <form wire:submit.prevent="acknowledgeClaverAward" novalidate>
                {{ $this->claverAwardForm }}
                <div class="mt-8">
                    <button type="submit" class="btn-primary {{ !$checked ? 'opacity-50' : ''}}">Acknowledge</button>
                </div>
            </form>
            @endif
        </div>

    </div>
</x-modal>

@endif