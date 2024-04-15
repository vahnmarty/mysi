<x-app-layout>

    <div class="px-4 py-12">

        <h2 class="text-2xl font-semibold text-center lg:text-3xl font-heading text-primary-blue">Welcome to the MySI Portal</h2>

        <div class="mt-8 space-y-8">
            <p>
                Welcome to MySI, your gateway to St. Ignatius College Preparatory!
            </p>

            @if(Auth::user()->account?->current_si_family)
            <p>
                <strong>Current SI Families:</strong>  Re-registration for the upcoming 2024-2025 school year must be completed by <strong>8:00 am PDT</strong> on <strong>Monday, April 22, 2024.</strong>  If you plan to attend SI, choose “Yes,” and fill out the Re-Registration form.  Make sure to carefully review the information and update as necessary.  If you plan to not attend SI, choose “No” and submit the form to notify SI.
            </p>
            <p>
                <strong>NOTE: </strong> Do NOT fill out the registration form on multiple screens as this will cause problems in saving the correct version.
            </p>
            <p>
                If you have any questions about re-registration, you may contact the Jeannie Quesada at <a href="mailto:jquesada@siprep.org" class="text-link">jquesada@siprep.org</a> or <a href="tel:(415) 731-7500 ext. 5236">(415) 731-7500 ext. 5236</a>.
            </p>
            @endif

            <p>
                <strong>Transfer Applicants:</strong>  The Transfer Application is now available!  The deadline to submit an application is <strong>midnight</strong> on <strong>Friday, May 10, 2024.</strong>  Immediately after submitting your application, please email <a href="mailto:admissions@siprep.org" class="text-link">admissions@siprep.org</a> with complete transcripts for the 2022-2023 academic school year and your 1st semester grades for the 2023-2024 academic school year.  Please note, an Admissions Video is now required of all transfer applicants and must be submitted by <strong>midnight</strong> on <strong>Friday, May 10, 2024.</strong>  You can find instructions and how to submit your Admissions Video at <a href="https://www.siprep.org/admissions/apply/admissions-video" class="text-link" target="_blank">https://www.siprep.org/admissions/apply/admissions-video.</a>
            </p>

            <p>
                <strong>NOTE: </strong> Do NOT fill out the transfer application form on multiple screens as this will cause problems in saving the correct version.
            </p>

            <p>
                If you have any questions about applying to SI, you may contact the Admissions Office at <a href="mailto:admissions@siprep.org" class="text-link">admissions@siprep.org</a> or <a href="tel:(415) 731-7500 ext. 5274">(415) 731-7500 ext. 5274</a>.
            </p>

            <p>
                If you have any technical issues using MySI, you may email <a href="mailto:mysi_admin@siprep.org" class="text-link ">mysi_admin@siprep.org</a> with a detailed description of what you are trying to do and what you are experiencing. A screenshot will help greatly in resolving your technical issue.
            </p>

            <p>
                <strong>System Requirements:</strong>  Windows 11 for PCs and Big Sur or later versions for Macs.
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
