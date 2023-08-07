<x-mail::message>

<x-slot:logo>
<img src="{{ asset('img/logo.png') }}" style="width: 64px; height: auto; margin:0 auto; margin-bottom: 30px; display: block">
</x-slot:logo>

<style>
    .text-center{
        text-align: center;
    }

    #appLogo{
        margin: 0 auto !important;
    }
</style>


<p class="text-center">Hi {{ $user->first_name}},</p>

<p class="text-center"><strong>Applicant Name:</strong>  {{ $app->student->first_name }}  {{ $app->student->last_name }}</p>

<p class="text-center">
    Thank you for submitting your application to St. Ignatius College Preparatory.
</p>

<p class="text-center">
    If you have any questions regarding the Admission process, please visit our website at <a href="https://www.siprep.org/admissions">https://www.siprep.org/admissions</a> or email us at <a href="mailto:admissions@siprep.org">admissions@siprep.org</a>
</p>


<p class="text-center" style="font-weight: bold">
    Regards, 
    <br> SI Admissions
</p>


<code class="text-center" style="display: block; margin: 0 auto;">
    Admissions Office<br>
    St. Ignatius College Preparatory<br>
    2001 37th Avenue<br>
    San Francisco, CA 94116<br>
    (415) 731-7500 ext. 5274
</code>

</x-mail::message>
