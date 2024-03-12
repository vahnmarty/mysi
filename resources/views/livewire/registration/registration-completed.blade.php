<div class="pb-16">
    <section>
        <div>
            <div class="flex justify-center">
                <img src="{{ asset('img/mail.svg') }}" class="w-24 h-24"/>
            </div>
            <div class="mt-8 text-center">
                <h1 class="text-3xl font-bold font-heading">Registration <span class="text-primary-blue">Completed</span></h1>
                <p class="mt-6">
                    Thank you for completing registration for the {{ app_variable('academic_school_year') }} school year.  
                    We are excited to welcome your family to SI.
                </p>
            </div>
        </div>

        <div class="mt-8">
            {{ $this->form }}
        </div>


    </section>


</div>
