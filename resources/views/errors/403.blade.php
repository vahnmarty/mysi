@extends('errors::layout')

@section('content')
<div class="flex flex-col items-center justify-center">

    <h2 class="text-4xl font-extrabold">Error 403</h2>

    <img src="{{ asset('img/error.jpg') }}" class="w-64 h-auto">
    
    <h3 class="mt-8 text-2xl font-bold">Access Forbidden.</h3>
    <p class="mt-4 text-sm text-gray-700">{{  __($exception->getMessage() ?: 'Forbidden') }}</p>
</div>
@endsection
