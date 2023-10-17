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
                {!! $content !!}
            </article>

            <p class="mt-8 font-bold text-gray-900">NOTE:  In order to reserve your spot in the SI Class of 2027, you must click the Enroll at SI button AND make a deposit payment before 6:00 am PT on Friday, March 24, 2023.   Your spot will not be reserved if you only click the Enroll at SI button and do not submit your deposit payment.</p>

            <div class="grid grid-cols-5 mt-8">
                <div class="col-span-2 p-3 border border-red-100 border-dashed rounded-md shadow-md bg-gray-50">
                    <div class="flex gap-3">
                        <x-heroicon-o-newspaper class="w-10 h-10 shadow-sm text-primary-red"/>
                        <div>
                            <p class="text-base font-bold">Financial Aid Assisstance</p>
                            <div class="flex items-center gap-4">
                                <a href="{{ route('notifications.financial-aid', $app->uuid) }}" class="underline">View</a>
                                <p class="italic" >Acknowledged</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-6 mt-8">
                <a href="{{ route('notifications.pdf', $app->uuid) }}" target="_blank" class="btn-primary">Download</a>
            </div>

        </div>
    </div>
</div>

@endsection