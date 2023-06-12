<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Models\School;
use App\Models\Address;
use App\Enums\AddressType;
use Filament\Forms\Components\Hidden;
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
                    Hidden::make('id'),
                    Select::make('address_type')
                        ->options(AddressType::asSameArray())
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $id = $get('id');

                            if(!$id){

                                $address = Address::create([
                                    'account_id'    => $this->app->account_id,
                                    'address_type'  => $state
                                ]);

                                $id = $address->id;
                            }
                            $this->autoSaveAddress($id, 'address_type', $state);
                        }),
                    TextInput::make('address')
                        ->disabled(fn(Closure $get) => !$get('address_type') )
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveAddress($get('id'), 'address', $state);
                        })
                        ->required(),
                    TextInput::make('city')
                        ->disabled(fn(Closure $get) => !$get('address_type') )
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveAddress($get('id'), 'city', $state);
                        })
                        ->required(),
                    Select::make('state')
                        ->options(us_states())
                        ->disabled(fn(Closure $get) => !$get('address_type') )
                        ->required()
                        ->preload()
                        ->searchable()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveAddress($get('id'), 'state', $state);
                        }),
                    TextInput::make('zip_code')
                        ->disabled(fn(Closure $get) => !$get('address_type') )
                        ->numeric()
                        ->minLength(4)
                        ->maxLength(5)
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveAddress($get('id'), 'zip_code', $state);
                        }),
                    TextInput::make('phone_number')
                        ->disabled(fn(Closure $get) => !$get('address_type') )
                        ->label('Primary Phone Number at Location:')
                        ->tel()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveAddress($get('id'), 'phone_number', $state);
                        })
                ])
                ->createItemButtonLabel('Add Address')
                ->cloneable()
                ->defaultItems(1)
        ];
    }

    private function autoSaveAddress($id, $column, $value)
    {
        $address = Address::find($id);
        $address->$column = $value;
        $address->save();
    }

}