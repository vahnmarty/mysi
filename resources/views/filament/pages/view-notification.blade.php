<div>
    <div class="text-sm">
    
        <div class="px-8 mx-auto max-w-7xl">

            @if($decision_status == 'Accepted')
            <div class="mt-8">
                <h4 class="font-bold text-primary-red">
                    You have successfully reserved your spot for the SI Class of {{ app_variable('class_year') }}!  We are thrilled to have you join our school community!  Registration will open on <u>{{ app_variable('registration_start_date') }}</u>.  Please log back into the MySI portal at that time to register for the {{ app_variable('academic_school_year') }} school year. 
                </h4>
                
                <h4 class="mt-4 font-bold text-primary-red">
                    In the meantime, feel free to browse our <a href="https://www.siprograms.com/" target="_blank" class="text-link">SI Summer Programs</a> offerings for the incoming Frosh class and visit the SI Prep Shop in person or <a href="https://siprepshop.com/" target="_blank" class="text-link">online</a>.  Click on the SI Prep Shop link on the menu for store hours.  Welcome to SI!
                </h4>

                @if($app->survey?->submitted())
                <p class="mt-4 underline">Please take our survey (Completed)</p>
                @else
                <a href="{{ route('survey-form', $app->survey?->uuid) }}" class="block mt-4 font-bold text-link">Please take our survey (Not Completed)</a>
                @endif
            </div>
            @elseif($decision_status == 'Declined')
            <div class="mt-8">
                <h4 class="font-bold text-primary-red">
                    This is confirmation that you declined your acceptance for the SI Class of {{ app_variable('class_year') }}. We wish you all the best in high school!
                </h4>

                @if($app->survey?->submitted())
                <p class="mt-4 underline">Please take our survey (Completed)</p>
                @else
                <a href="{{ route('survey-form', $app->survey?->uuid) }}" class="block mt-4 font-bold text-link">Please take our survey (Not Completed)</a>
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
                    NOTE: In order to reserve your spot in the SI Class of {{ app_variable('class_year') }}, you must click the Enroll at SI button AND make a deposit payment before {{  app_variable('registration_end_date') }}. Your spot will not be reserved if you only click the Enroll at SI button and do not submit your deposit payment.
                </p>
                @endif

                @if($app->appStatus)
                    @if($app->appStatus->financial_aid || $app->appStatus->claver_award || $app->appStatus->product_design)
                    <div class="p-6 mt-8 bg-blue-100 border-2 border-blue-300 rounded-md shadow-sm">
                        <div class="flex gap-3">
                            <x-heroicon-s-information-circle class="flex-shrink-0 text-blue-800 w-7 h-7"/>
                            <p>The <strong>Enroll at SI</strong> button will appear at the bottom of this page once you have clicked on and acknowledged each letter below.</p>
                        </div>
                    </div>
                    @endif
                @endif
    
                
                
                @if(!empty($faq))

                <div class="mt-8">
                    <a href="#" x-data="{}"
                    x-on:click.prevent="$dispatch('open-modal', 'show-faq')" 
                    class="text-xl font-bold text-color-link">Waitlist FAQ</a>
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
                
                <div class="grid grid-cols-6 gap-3 mt-8">
                    @if($app->appStatus->financial_aid)
                        @include('filament.pages.letters.financial-aid')
                    @endif

                    @include('filament.pages.letters.claver-award')

                    @include('filament.pages.letters.product-design')

                    
                </div>


                <div class="hidden mt-8 space-y-3 w-96">

                    <h3 class="text-xl font-bold">Attachments</h3>

                    <div class="px-3 py-3 bg-gray-100 border-b border-dashed rounded-md">
                        <div class="flex gap-3">
                            <x-heroicon-o-mail-open class="self-center flex-shrink-0 text-gray-700 w-7 h-7"/>
                            <div>
                                <h3 class="text-lg text-gray-700">Financial Assistance Letter</h3>
                                <div class="flex gap-3 text-xs divide-x">
                                    <a href="#" class="text-link">Open</a>
                                    <p class="pl-2 text-gray-500">Acknowledged at February 14, 2004</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-3 py-3 bg-gray-100 border-b border-dashed rounded-md">
                        <div class="flex gap-3">
                            <x-heroicon-s-mail class="self-center flex-shrink-0 text-gray-900 w-7 h-7"/>
                            <div>
                                <h3 class="text-lg font-bold">Claver Award Letter</h3>
                                <div class="flex gap-3 text-xs divide-x">
                                    <a href="#" class="text-link">Open</a>
                                    <!-- <p class="pl-2 text-gray-500">Acknowledged at February 14, 2004</p> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-3 py-3 bg-gray-100 border-b border-dashed rounded-md">
                        <div class="flex gap-3">
                            <x-heroicon-s-mail class="self-center flex-shrink-0 text-gray-900 w-7 h-7"/>
                            <div>
                                <h3 class="text-lg font-bold">Product Design Letter</h3>
                                <div class="flex gap-3 text-xs divide-x">
                                    <a href="#" class="text-link">Open</a>
                                    <!-- <p class="pl-2 text-gray-500">Acknowledged at February 14, 2004</p> -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
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