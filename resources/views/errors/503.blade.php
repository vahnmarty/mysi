@extends('errors::minimal')

@section('content')
<div class="flex flex-col items-center justify-center h-screen">

    <h2 class="text-4xl font-extrabold">Error 503</h2>

    <img src="{{ asset('img/error.jpg') }}" class="w-64 h-auto">
    
    <h3 class="mt-8 text-2xl font-bold">We're under maintenance.</h3>
    <p class="mt-4 text-sm text-gray-700">Please check back soon just putting little touch up on some pretty updates.</p>
</div>
@endsection
