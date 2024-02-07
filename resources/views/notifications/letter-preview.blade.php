@extends('layouts.guest')

@section('content')
<div >
    
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

            <article class="mt-16 html-content">
                {!! $content !!}
            </article>


            <div class="mt-8">
                <a href="{{ url('notification-sample/' . $notification->id .'?application_id=' . $app->id . '&pdf=true') }}" target="_blank" class="btn-primary">Download</a>
            </div>

        </div>
    </div>
</div>

@endsection