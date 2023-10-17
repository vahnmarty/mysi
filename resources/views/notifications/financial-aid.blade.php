@extends('layouts.app')

@section('content')
<div class="text-sm">
    
    <div class="px-8 mx-auto max-w-7xl">
        <div class="p-10 mt-8 bg-white border rounded-lg shadow-lg">

            <header class="flex gap-6">
                <img src="{{ asset('img/logo.png') }}" alt="">
                <div>
                    <p>
                        St. Ignatius College Preparatory<br>
                        2001 37th Avenue<br>
                        San Francisco, California 94116<br>
                        (415) 731-7500<br>
                    </p>
                    <p class="mt-6"> Office of Admissions</p>
                </div>
            </header>

            <article class="mt-16">
                
            </article>

            <div class="flex gap-6 mt-8">
                <a class="btn-primary">Acknowledged</a>
            </div>

        </div>
    </div>
</div>

@endsection