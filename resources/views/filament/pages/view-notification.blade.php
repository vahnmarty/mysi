<x-filament::page>

    @if($declined)
    <div class="p-4 bg-red-300 rounded-md">
        <div class="flex items-center">
            <x-heroicon-s-exclamation class="w-16 h-16 text-red-600"/>
            <div>
                <h5 class="text-lg font-bold">You have declined this notification.</h5>
            </div>
        </div>
    </div>
    @endif
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
    
                <article class="mt-16">
                    {!! $content !!}
                </article>
    
                <div class="mt-8 border-t-2 border-dashed border-primary-red"></div>
                
                @if(!empty($faq))
                {!! $faq !!}
                @endif
    
                <p class="mt-8 font-bold text-gray-900">NOTE:  In order to reserve your spot in the SI Class of 2027, you must click the Enroll at SI button AND make a deposit payment before 6:00 am PT on Friday, March 24, 2023.   Your spot will not be reserved if you only click the Enroll at SI button and do not submit your deposit payment.</p>
    
                @if($app->with_financial_aid)
                <div class="grid grid-cols-5 mt-8">
                    <div class="col-span-2 p-3 bg-red-100 border border-red-100 border-dashed rounded-md shadow-md">
                        <div class="flex gap-3">
                            <x-heroicon-o-newspaper class="w-10 h-10 shadow-sm text-primary-red"/>
                            <div>
                                <p class="text-base font-bold">Financial Aid Assisstance</p>
                                <div class="flex items-center gap-4">
                                    <a href="#" x-data="{}"
                                    x-on:click.prevent="$dispatch('open-modal', 'show-financial-aid')" 
                                    class="underline">View</a>
                                    @if($notification->fa_acknowledged_at)
                                    <p class="italic" >Acknowledged</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
                                <p class="mt-6"> Office of Admissions</p>
                            </div>
                        </header>
            
                        <article class="mt-16">
                            {!! $fa_content !!}
                        </article>
            
                        <div class="flex gap-6 pt-8 mt-8 border-t border-dashed border-primary-red">
                            
                            @if($notification->acknowledged())
                            <p>Acknowledged last {{ date('F d, Y', strtotime($notification->fa_acknowledged_at)) }}.</p>
                            @else
                            <form wire:submit.prevent="acknowledgeFinancialAid">
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
    
                <div class="flex justify-between gap-6 mt-8">
                    <a href="#" wire:click="mountAction('financial_aid')"  class="btn-primary">
                        <x-heroicon-o-document-download class="w-4 h-4 mr-3 text-white"/>
                        Download
                    </a>
                </div>
    
            </div>
        </div>
    </div>
</x-filament::page>