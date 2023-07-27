<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use Illuminate\Support\HtmlString;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Wiebenieuwenhuis\FilamentCharCounter\Textarea;

trait FinalStepsTrait{

    public function getFinalStepsForm()
    {
        return [
            Placeholder::make('Documentation')
                ->label('')
                ->content(new HtmlString('
                <div>
                    <section>
                        <h4 class="text-xl font-bold text-center font-heading text-primary-blue">Release Authorization</h4>
                        <p class="mt-8 text-sm">
                            I hereby consent to the release of my child`s academic records and test scores to St. Ignatius College Preparatory for the purpose of evaluating his or her application for admission. In authorizing this release, I acknowledge that I waive my right to read the Confidential School/Clergy Recommendations and my child`s application file. I further consent to St. Ignatius College Preparatory issuing academic progress reports to my child`s current school listed on this application during my child`s four years at St. Ignatius College Preparatory.
                        </p>
                    </section>
                </div>
            ')),
            Checkbox::make('agree_to_release_record')
                ->columnSpan('full')
                ->label('By checking this box, you acknowledge that you have read, understand and agree to the above.')
                ->lazy()
                ->required()
                ->afterStateUpdated(function(Closure $get, $state){
                    $this->autoSave('agree_to_release_record', $state);
                }),
            Checkbox::make('agree_academic_record_is_true')
                ->columnSpan('full')
                ->label('By checking the box, I (parent(s)/guardian(s))
                declare that to the best of my knowledge, the information provided in the
                application submitted to St. Ignatius College Preparatory on this online application
                is true and complete.')
                ->lazy()
                ->required()
                ->afterStateUpdated(function(Closure $get, $state){
                    $this->autoSave('agree_academic_record_is_true', $state);
                }),
            Placeholder::make('Continue')
                ->label('')
                ->visible(fn(Closure $get) => $get('agree_to_release_record') && $get('agree_academic_record_is_true') )
                ->content(new HtmlString('
                    <div class="flex justify-center">
                        <button type="button" wire:click="validateForm" class="flex btn-primary">
                        <span class="mr-3 text-white animate-spin" wire:loading wire:target="validateForm">*</span>
                        Continue
                        </button>
                </div>')),
            Fieldset::make('Payment Information')
                ->label(new HtmlString('<strong>Payment Information</strong>'))
                ->visible(fn(Closure $get) => $this->is_validated && $get('agree_to_release_record') && $get('agree_academic_record_is_true') )
                ->columns(3)
                ->schema([
                    Placeholder::make('amount')
                        ->columnSpan('full')
                        ->label('')
                        ->content(new HtmlString('
                            <div>
                                <h4>Amount Due: <strong class="font-bold text-primary-red">$'. number_format($this->amount, 2).'</strong></h4>
                            </div>    
                        '))
                        ->reactive(),
                    TextInput::make('billing.first_name')
                        ->label('First Name')
                        ->required()
                        ->lazy(),
                    TextInput::make('billing.last_name')
                        ->label('Last Name')
                        ->required()
                        ->lazy(),
                    TextInput::make('billing.email')
                        ->label('Email')
                        ->email()
                        ->rules(['email:rfc,dns'])
                        ->columnSpan('full')
                        ->required()
                        ->lazy(),
                    TextInput::make('billing.card_number')
                        ->label('Credit Card Number')
                        ->required()
                        ->minLength(13)
                        ->maxLength(16),
                        //->mask(fn (TextInput\Mask $mask) => $mask->pattern('{0000}-{0000}-{0000}-{0000}')),
                    TextInput::make('billing.card_cvv')
                        ->maxLength(3)
                        ->minLength(3)
                        ->required()
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('{000}'))
                        ->label('CVV'),
                    TextInput::make('billing.card_expiration')
                        ->label(new HtmlString('Expiration <strong>(MM/YYYY)</strong>'))
                        ->placeholder('e.g. 06/2030')
                        ->required()
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('{00}/{0000}')),
                    TextInput::make('billing.address')
                        ->label('Billing Address')
                        ->required(),
                    TextInput::make('billing.city')
                        ->label('Billing City')
                        ->required(),
                    TextInput::make('billing.state')
                        ->label('Billing State')
                        ->required(),
                    TextInput::make('billing.zip_code')
                        ->label('Billing Zip Code')
                        ->required(),
                    // Placeholder::make('note')
                    //     ->label('')
                    //     ->columnSpan('full')
                    //     ->content(new HtmlString('
                    //         <p class="text-sm">
                    //         <strong class="text-primary-red">NOTE:</strong>  
                    //         Before you click on the Pay button, please make
                    //         sure your information is correct.  You will not
                    //         be able to edit payment information after you
                    //         click the Pay button because SI does not store
                    //         your credit information.  If you are not ready
                    //         to make a payment, click on the Close button.
                    //         </p>
                    //     '))
                ])
        ];
    }

}