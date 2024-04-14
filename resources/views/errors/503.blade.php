@extends('errors::minimal')

@section('content')
<div class="flex flex-col items-center justify-center h-screen">

    <h2 class="text-4xl font-extrabold">Error 503</h2>

    <img src="{{ asset('img/error.jpg') }}" class="w-64 h-auto">
    
    <h3 class="mt-8 text-2xl font-bold">We're under maintenance.</h3>
    <p class="mt-4 text-sm text-center text-gray-700">
        The system is currently unavailable. We are currently working on preparing it for Re-Registration.<br> Please come back on Monday, April 15 at 8 am PDT.
    </p>
</div>
@endsection
