<x-app-layout>

    <div class="px-4 py-12">

        <h2 class="text-2xl font-semibold text-center lg:text-3xl font-heading text-primary-blue">Welcome to the MySI Portal</h2>
        
        <h3 class="mt-8 text-lg font-bold text-gray-700 font-heading">September 6, 2023</h3>

        <div class="mt-8 space-y-8">
            <p>
                Welcome to MySI, your gateway to St. Ignatius College Preparatory!
            </p>
            <p>
                You will create and complete your Admissions Application here and submit it by midnight on Wednesday, November 15, 2023. In March, you will receive your Admissions decision using this system. Make sure to save your username and password in a secure location.

            </p>
            <p>
                Use the navigation links located on the left-hand side of your screen to explore MySI.
            </p>

            <p>
                If you have any questions about applying to SI, you may contact the Admissions Office at <a href="mailto:admissions@siprep.org" class="underline text-link">admissions@siprep.org</a> or (415) 731-7500 ext. 5274.
            </p>

            <p>
                If you have any technical issues using MySI, you may email <a href="mailto:mysi_admin@siprep.org" class="underline text-link">mysi_admin@siprep.org</a> with a detailed description of what you are trying to do and what you are seeing.  A screenshot will help greatly in resolving your technical issue.
            </p>
            
        </div>

        <div class="flex justify-center mt-12">
            <a href="{{ route('application.admission') }}" class="btn-primary">Start Application</a>
        </div>
    </div>
</x-app-layout>
