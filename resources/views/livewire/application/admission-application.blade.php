<div>
    <h2 class="text-2xl font-semibold font-heading text-primary-blue">
        Admissions Application
    </h2>

    <section x-data="{ open: $wire.entangle('open') }">
        <div class="mt-8" x-show="open">
            {{ $this->table }}
        </div>
        <div class="mt-8" x-show="!open" x-cloak>


            <div class="max-w-lg mx-auto text-center">
                <img src="{{ asset('img/error.jpg') }}" class="w-64 h-auto mx-auto">
            
                <h3 class="mt-8 text-2xl font-bold">Application Period Ended.</h3>
                <p class="mt-4 text-sm text-gray-700">
                    We regret to inform you that the admission application period has come to a close. Thank you for your interest. Stay tuned for future opportunities.
                </p>
            </div>
        </div>
    </section>


</div>
