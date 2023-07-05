<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Models\School;
use App\Models\Address;
use App\Enums\AddressType;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput\Mask;

trait AddressFormTrait{

    public function getAddressForm()
    {
        return [
            Repeater::make('addresses')
                ->label('')
                ->disableItemMovement()
                ->maxItems(4)
                ->registerListeners([
                    'repeater::deleteItem' => [
                        function (Component $component, string $statePath, string $uuidToDelete): void {
                            $items = $component->getState();
                            $addresses = Address::where('account_id', $this->app->account_id)->get();

                            foreach($addresses as $index => $address){
                                $existing = collect($items)->where('id', $address->id)->first();

                                if(!$existing){
                                    $address->delete();
                                }
                            }
                        },
                    ],
                ])
                ->schema([
                    Hidden::make('id'),
                    Select::make('address_type')
                        ->label('Address Type')
                        ->options(function(Closure $get){
                            $current = Address::where('account_id', accountId())->where('id', '!=', $get('id'))->pluck('address_type', 'address_type')->toArray();
                            $array = AddressType::asSameArray();
                            $types = [];

                            foreach($array as $type)
                            {
                                if(!in_array($type, $current)){
                                    $types[] = $type;
                                }
                            }


                            return array_combine($types, $types);
                        })
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function(Closure $get, Closure $set, $state){
                            $id = $get('id');

                            if(!$id){

                                $address = Address::create([
                                    'account_id'    => $this->app->account_id,
                                    'address_type'  => $state
                                ]);

                                $id = $address->id;

                                $set('id', $id);
                            }
                            $this->autoSaveAddress($id, 'address_type', $state);
                        }),
                    TextInput::make('address')
                        ->label('Address')
                        ->disabled(fn(Closure $get) => !$get('address_type') )
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveAddress($get('id'), 'address', $state);
                        })
                        ->required(),
                    TextInput::make('city')
                        ->label('City')
                        ->disabled(fn(Closure $get) => !$get('address_type') )
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveAddress($get('id'), 'city', $state);
                        })
                        ->required(),
                    Select::make('state')
                        ->label('State')
                        ->options(us_states())
                        //->disabled(fn(Closure $get) => !$get('address_type') )
                        ->required()
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function(Closure $get, $state){
                            if($get('id')){
                                $this->autoSaveAddress($get('id'), 'state', $state);
                            }else{
                                Notification::make()
                                ->title('Error! Please select Address Type first.')
                                ->danger()
                                ->send();
                            }
                        }),
                    TextInput::make('zip_code')
                        ->label('ZIP Code')
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
                        ->label('Phone Number')
                        ->disabled(fn(Closure $get) => !$get('address_type') )
                        ->label('Primary Phone Number at Location:')
                        ->mask(fn (Mask $mask) => $mask->pattern('(000) 000-0000'))
                        ->tel()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveAddress($get('id'), 'phone_number', $state);
                        })
                ])
                ->createItemButtonLabel('Add Address')
        ];
    }

    private function autoSaveAddress($id, $column, $value)
    {
        $address = Address::find($id);
        $address->$column = $value;
        $address->save();
    }

}