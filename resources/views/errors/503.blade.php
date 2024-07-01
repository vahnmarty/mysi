@extends('errors::minimal')

@section('content')
<div class="flex flex-col items-center justify-center h-screen">

    <h2 class="text-4xl font-extrabold">Error 503</h2>

    <img src="{{ asset('img/error.jpg') }}" class="w-64 h-auto">
    
    <h3 class="mt-8 text-2xl font-bold">We're under maintenance.</h3>
    <p class="mt-4 text-sm text-center text-gray-700">
        The MySI Portal is currently under system maintenance. It will be back up on Monday, July 1, 2024 at 10 am PT.<br><br>
        For the Class of 2028, click on the link to download the <A HREF = "/files/SIFreshmanHealthForm.pdf"><FONT COLOR = "blue">SFUSD Health Form</FONT></A>.  Send the completed form, with the doctor's signature, to <A HREF = "mailto:mysi_admin@siprep.org?subject=SFUSD Health Form"><FONT COLOR = "blue">mysi_admin@siprep.org</FONT></A> along with the student's name.
    </p>
</div>
@endsection
