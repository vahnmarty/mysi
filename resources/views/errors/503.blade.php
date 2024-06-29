@extends('errors::minimal')

@section('content')
<div class="flex flex-col items-center justify-center h-screen">

    <h2 class="text-4xl font-extrabold">Error 503</h2>

    <img src="{{ asset('img/error.jpg') }}" class="w-64 h-auto">
    
    <h3 class="mt-8 text-2xl font-bold">We're under maintenance.</h3>
    <p class="mt-4 text-sm text-center text-gray-700">
        The MySI Portal is currently under system maintenance. It will be back up on Monday, July 1, 2024 at 8 am PT.<br><br>
        For the Class of 2028, if you need to upload your SFUSD Health Form, please email the documents along with the student name to <A HREF = "mailto:mysi_admin@siprep.org?subject=SFUSD Health Form"><FONT COLOR = "blue">mysi_admin@siprep.org</FONT></A>.
    </p>
</div>
@endsection
