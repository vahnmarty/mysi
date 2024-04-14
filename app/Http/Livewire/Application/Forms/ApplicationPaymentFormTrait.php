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

trait ApplicationPaymentFormTrait{

    public function getPaymentForm()
    {
        return Fieldset::make('Payment Information')
                ->label(new HtmlString('<strong>Payment Information</strong>'))
                ->visible(fn(Closure $get) => $this->is_validated && $get('agree_to_release_record') && $get('agree_academic_record_is_true') && $this->amount > 0 )
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
                        ->required(),
                    TextInput::make('billing.last_name')
                        ->label('Last Name')
                        ->required(),
                    TextInput::make('billing.email')
                        ->label('Email')
                        ->email()
                        //->rules(['email:rfc,dns'])
                        ->columnSpan('full')
                        ->required(),
                    TextInput::make('billing.card_number')
                        ->label('Credit Card Number')
                        ->validationAttribute('Credit Card Number')
                        ->required()
                        ->minLength(16)
                        ->maxLength(16)
                        ->mask(fn(TextInput\Mask $mask) => $mask
                            ->numeric()
                         ),
                        //->mask(fn (TextInput\Mask $mask) => $mask->pattern('{0000}-{0000}-{0000}-{0000}')),
                    TextInput::make('billing.card_cvv')
                        ->maxLength(4)
                        ->minLength(3)
                        ->required()
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('{0000}'))
                        ->validationAttribute('CVV')
                        ->label('CVV (3 or 4)'),
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
                        ->required()
                        ->maxLength(5)
                        ->mask(fn(TextInput\Mask $mask) => $mask
                            ->numeric()
                         ),
                ]);
    }

}