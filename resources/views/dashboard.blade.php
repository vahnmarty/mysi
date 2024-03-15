<x-app-layout>

    <div class="px-4 py-12">

        <h2 class="text-2xl font-semibold text-center lg:text-3xl font-heading text-primary-blue">Welcome to the MySI Portal</h2>

        <div class="mt-8 space-y-8">
            <p>
                Welcome to MySI, your gateway to St. Ignatius College Preparatory!
            </p>
            <p>
                For those that submitted an application for the SI Class of 2028, your Admissions decision will be available on March 15, 2024 at 4pm. You can access it by clicking on the "Admissions Decision" tab on the left hand side, which will become available at 4pm.
            </p>

            <p>
                At the same time, you will also be able to access your student's HSPT scores. You can access these by clicking on the "HSPT Scores" tab on the left hand side, which will become available at 4pm. This tab will include your student's scores as well as important information about the HSPT scores.
            </p>

            <p>
                If you have any questions, you may contact the Admissions Office at <a href="mailto:admissions@siprep.org" class="text-link">admissions@siprep.org</a> or <a href="tel:(415) 731-7500 ext. 5274" class="text-link">(415) 731-7500 ext. 5274</a>.
            </p>
            <!-- <p>
                Please visit the <a href="https://www.siprep.org/admissions/timeline" target="_blank" class="text-link">Timeline</a> on our Admissions website for next steps. Use the navigation links located on the left-hand side of your screen to explore MySI.
            </p>
            <p>
                If you have any questions about the application process, you may contact the Admissions Office at <a href="mailto:admissions@siprep.org" class="text-link ">admissions@siprep.org</a>  or <a href="tel:(415) 731-7500 ext. 5274" class="text-link">(415) 731-7500 ext. 5274</a>.
            </p> -->

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
