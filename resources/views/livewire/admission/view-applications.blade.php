<div>
    <h2 class="text-2xl font-semibold font-heading text-primary-blue">
        View Applications
    </h2>

    <section x-data="{ open: $wire.entangle('open') }">
        <div class="mt-8" x-show="open">
            {{ $this->table }}
        </div>
        <div class="mt-8" x-show="!open" x-cloak>


            <div class="max-w-lg mx-auto text-center">
                <img src="{{ asset('img/error.jpg') }}" class="w-64 h-auto mx-auto">
            
                <h3 class="mt-8 text-2xl font-bold">No applications found.</h3>
            </div>
        </div>
    </section>

</div>
