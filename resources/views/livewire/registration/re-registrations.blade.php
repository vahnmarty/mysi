<div>
    <h2 class="text-2xl font-semibold font-heading text-primary-blue">
        Re-Registrations
    </h2>

    <section x-data="{ open: $wire.entangle('open') }">
        <div class="mt-8" x-show="open">
            {{ $this->table }}
        </div>
        <div class="mt-8" x-show="!open" x-cloak>


        </div>
    </section>


</div>
