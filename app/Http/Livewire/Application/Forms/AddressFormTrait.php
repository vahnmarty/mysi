<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Models\School;
use App\Enums\AddressType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;

trait AddressFormTrait{

    public function getAddressForm()
    {
        return [
            Repeater::make('addresses')
                ->schema([
                    Select::make('address_type')
                        ->options(AddressType::asSameArray())
                        ->required(),
                    TextInput::make('address')->required(),
                    TextInput::make('city')->required(),
                    Select::make('state')
                        ->options(us_states())
                        ->required()
                        ->preload()
                        ->searchable(),
                    TextInput::make('zip_code')
                        ->numeric()
                        ->minLength(4)
                        ->maxLength(5)
                        ->required(),
                    TextInput::make('phone_number')
                        ->label('Primary Phone Number at Location:')
                        ->tel()
                ])
                ->createItemButtonLabel('Add Address')
                ->defaultItems(1)
        ];
    }

}