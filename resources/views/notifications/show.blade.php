@extends('layouts.app')

@section('content')
<div class="text-sm">
    
    <div class="px-8 mx-auto max-w-7xl">
        <div class="p-10 mt-8 bg-white border-2 rounded-lg shadow-xl">

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

            <div class="mt-8 border-t-2 border-dashed border-primary-red"></div>
            
            @if(!empty($faq))
            {!! $faq !!}
            @endif

            <p class="mt-8 font-bold text-gray-900">NOTE:  In order to reserve your spot in the SI Class of 2027, you must click the Enroll at SI button AND make a deposit payment before 6:00 am PT on Friday, March 24, 2023.   Your spot will not be reserved if you only click the Enroll at SI button and do not submit your deposit payment.</p>

            @if($app->with_financial_aid)
            <div class="grid grid-cols-5 mt-8">
                <div class="col-span-2 p-3 border border-red-100 border-dashed rounded-md shadow-md bg-gray-50">
                    <div class="flex gap-3">
                        <x-heroicon-o-newspaper class="w-10 h-10 shadow-sm text-primary-red"/>
                        <div>
                            <p class="text-base font-bold">Financial Aid Assistance</p>
                            <div class="flex items-center gap-4">
                                <a target="_blank" href="{{ route('notifications.financial-aid', $app->uuid) }}" class="underline">View</a>
                                @if($notification->fa_acknowledged_at)
                                <p class="italic" >Acknowledged</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="flex justify-between gap-6 mt-8">
                <a href="{{ route('notifications.pdf', $app->uuid) }}" target="_blank" class="btn-primary">Download</a>
                <div class="flex justify-center gap-3">
                    <button type="submit" class="btn-success">Enroll at SI</button>
                    <button type="submit" class="btn-danger">Decline</button>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection