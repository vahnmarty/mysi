<x-app-layout>

    <div class="px-4 py-12">

        <h2 class="text-2xl font-semibold text-center lg:text-3xl font-heading text-primary-blue">Welcome to the MySI Portal</h2>

        <div class="mt-8 space-y-8">
            <p>
                Welcome to MySI, your gateway to St. Ignatius College Preparatory!
            </p>
            <p>
                Congratulations to the SI Class of {{ app_variable('class_year') }}! We are so excited to welcome our newest Wildcats this fall!
            </p>

            <p>
                Frosh registration will become available beginning Monday, March 25th at 10am and must be completed by Monday, April 8th at 8am. For best results, if you are using a PC, make sure you are on Windows 11. If you are using a Mac, make sure you have macOS (Big Sur) or a later version (i.e. Monterey, Ventura or Sonoma). <B>NOTE:</B> Do NOT fill out the registration form on multiple screens as this will cause problems in saving the correct version.
            </p>

            <p>
                For all things Class of {{ app_variable('class_year') }}, visit the <a href="https://families.siprep.org/parents/welcome" target="_blank" class="text-link">Welcome Portal</a> for our incoming frosh families!
            </p>

            <p>
                If you have any technical issues using MySI, you may email <a href="mailto:mysi_admin@siprep.org" class="text-link ">mysi_admin@siprep.org</a> with a detailed description of what you are trying to do and what you are experiencing. A screenshot will help greatly in resolving your technical issue.
            </p>
            
        </div>

        @php
            $freshmen_application_start_date = notification_setting('freshmen_application_start_date');
            $freshmen_application_end_date = notification_setting('freshmen_application_hard_close_date');

            $app_start_date = $freshmen_application_start_date->value;
            $app_end_date = $freshmen_application_end_date->value;
        @endphp

        @if(now()->gte($app_start_date) && now()->lt($app_end_date) || empty($app_start_date)  || empty($app_end_date))
        <div class="flex justify-center mt-12">
            <a href="{{ route('application.admission') }}" class="btn-primary">Start Application</a>
        </div>
        @endif
    </div>
</x-app-layout>
