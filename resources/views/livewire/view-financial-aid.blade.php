<div>
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
            {!! $notification->fa_contents !!}
        </article>

        <div class="flex gap-6 pt-8 mt-8 border-t border-dashed border-primary-red">
            @if($notification->fa_acknowledged())
            <p>Acknowledged last {{ date('F d, Y', strtotime($notification->fa_acknowledged_at)) }}.</p>
            @else
            <form wire:submit.prevent="acknowledgeFinancialAid" novalidate>
                {{ $this->form }}
                <div class="mt-8">
                    <button type="submit" class="btn-primary {{ !$checked ? 'opacity-50' : ''}}">Acknowledge</button>
                </div>
            </form>
            @endif
        </div>

    </div>
</div>
