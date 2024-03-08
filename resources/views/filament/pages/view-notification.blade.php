<div>
    <div class="text-sm">
    
        <div class="px-8 mx-auto max-w-7xl">

            @if($decision_status == 'Accepted')
            <div class="mt-8">
                <h4 class="font-bold text-primary-red">
                    You have successfully reserved your spot for the SI Class of {{ app_variable('class_year') }}!  We are thrilled to have you join our school community!  Registration will open on <u>{{ app_variable('registration_start_date') }}</u>.  Please log back into the MySI portal at that time to register for the {{ app_variable('academic_school_year') }} school year. 
                </h4>
                
                <h4 class="mt-4 font-bold text-primary-red">
                    In the meantime, feel free to browse our <a href="https://www.siprograms.com/" target="_blank" class="text-link">SI Summer Programs</a> offerings for the incoming Frosh class and visit the SI Prep Shop in person or <a href="https://www.siprograms.com/" target="_blank" class="text-link">online</a>.  Click on the SI Prep Shop link on the menu for store hours.  Welcome to SI!
                </h4>

                @if($app->survey?->submitted())
                <p class="mt-4 underline">Please take our survey (Completed)</p>
                @else
                <a href="{{ route('survey-form', $app->survey?->uuid) }}" class="block mt-4 underline text-link">Please take our survey (Not Completed)</a>
                @endif
            </div>
            @elseif($decision_status == 'Declined')
            <div class="mt-8">
                <h4 class="font-bold text-primary-red">
                    This is a confirmation that you declined your acceptance to SI. We wish you all the best in high school.
                </h4>

                @if($app->survey?->submitted())
                <p class="mt-4 underline">Please take our survey (Completed)</p>
                @else
                <a href="{{ route('survey-form', $app->survey?->uuid) }}" class="block mt-4 underline text-link">Please take our survey (Not Completed)</a>
                @endif
            </div>
            @elseif($decision_status == 'Waitlist Removed')
            <div class="mt-8">
                <h4 class="font-bold text-primary-red">
                    This is a confirmation that you removed yourself from the Waiting list. We wish you all the best in high school.
                </h4>

            </div>
            @endif

            <div class="p-10 mt-8 bg-white border-2 rounded-lg shadow-md">
    
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

                
                @if($app->applicationAccepted())
                <p class="mt-8 font-bold text-gray-900">
                    NOTE: In order to reserve your spot in the SI Class of {{ config('settings.class_year') }}, you must click the Enroll at SI button AND make a deposit payment before {{  app_variable('registration_end_date') }}. Your spot will not be reserved if you only click the Enroll at SI button and do not submit your deposit payment.
                </p>
                @endif
    
                
                
                @if(!empty($faq))

                <div class="mt-8">
                    <a href="#" x-data="{}"
                    x-on:click.prevent="$dispatch('open-modal', 'show-faq')" 
                    class="text-xl font-bold underline text-link">Waitlist FAQ</a>
                </div>

                <x-modal name="show-faq" :show="false"  maxWidth="4xl">
                    <div class="p-10 bg-white border rounded-lg shadow-lg">

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
                            {!! $faq !!}
                        </article>
                    </div>
                </x-modal>
                
                @endif
                
                @if($app->applicationAccepted())
                @if($app->appStatus->financial_aid)
                <div class="grid grid-cols-5 mt-8">
                    @if($app->fa_acknowledged())
                    <div class="col-span-2 p-3 bg-green-200 border border-red-100 border-dashed rounded-md shadow-md">
                        <div class="flex gap-3">
                            <x-heroicon-o-check-circle class="w-10 h-10 text-green-500 shadow-sm"/>
                            <div>
                                <p class="text-base font-bold">Financial Aid Assistance</p>
                                <div class="flex items-center gap-4">
                                    <a href="#" x-data="{}"
                                    x-on:click.prevent="$dispatch('open-modal', 'show-financial-aid')" 
                                    class="underline">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col-span-2 p-3 bg-red-100 border border-red-100 border-dashed rounded-md shadow-md">
                        <div class="flex gap-3">
                            <x-heroicon-o-newspaper class="w-10 h-10 shadow-sm text-primary-red"/>
                            <div>
                                <p class="text-base font-bold">Financial Aid Assistance</p>
                                <div class="flex items-center gap-4">
                                    <a href="#" x-data="{}"
                                    x-on:click.prevent="$dispatch('open-modal', 'show-financial-aid')" 
                                    class="underline">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <x-modal name="show-financial-aid" :show="$show_fa"  maxWidth="4xl">
                    <div class="p-10 bg-white border rounded-lg shadow-lg">

                        <header class="flex gap-6">
                            <img src="{{ asset('img/logo.png') }}" alt="">
                            <div>
                                <p>
                                    St. Ignatius College Preparatory<br>
                                    2001 37th Avenue<br>
                                    San Francisco, California 94116<br>
                                    (415) 731-7500<br>
                                </p>
                                <p class="mt-6"> Business Office</p>
                            </div>
                        </header>
            
                        <article class="mt-16 html-content">
                            {!! $fa_content !!}
                        </article>
            
                        <div class="flex gap-6 pt-8 mt-8 border-t border-dashed border-primary-red">
                            
                            @if($app->fa_acknowledged())
                            <p>Acknowledged last {{ date('F d, Y', strtotime($app->appStatus->fa_acknowledged_at)) }}.</p>
                            @else
                            <form wire:submit.prevent="acknowledgeFinancialAid" novalidate>
                                {{ $this->form }}
                                <div class="mt-8">
                                    <button type="submit" class="btn-primary {{ !$checked ? 'opacity-50' : ''}}">Acknowledge</button>
                                </div>
                            </form>
                            @endif
                        </div>
            
                    </div>
                </x-modal>
                @endif

               
                
                @if($app->applicationAccepted())
                <div class="flex justify-between gap-6 mt-8">
                    <a href="{{ route('notifications.pdf', $notification->uuid) }}"   class="btn-primary">
                        <x-heroicon-o-document-download class="w-4 h-4 mr-3 text-white"/>
                        Download
                    </a>
                </div>
                @endif
    
            </div>

            <div class="mt-8">
                <x-custom-filament-page></x-custom-filament-page>
            </div>
        </div>
    </div>
</div>