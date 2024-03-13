<?php

namespace App\Http\Livewire\Registration\Forms;

use Closure;
use Livewire\Component as Livewire;
use App\Models\School;
use App\Models\Address;
use App\Enums\AddressType;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput\Mask;

trait AddressFormTrait{

    public function getAddressForm()
    {
        return [
            Placeholder::make('address_form_description')
                ->label('')
                ->content(new HtmlString('* This section is to be completed by a parent/guardian.')),
            Repeater::make('addresses')
                ->label('')
                ->disableItemMovement()
                ->minItems(1)
                ->maxItems(4)
                ->registerListeners([
                    'repeater::deleteItem' => [
                        function (Component $component, string $statePath, string $uuidToDelete): void {

                            if($statePath == 'data.addresses')
                            {
                                $items = $component->getState();
                                $addresses = Address::where('account_id', $this->registration->account_id)->get();

                                foreach($addresses as $index => $address){
                                    $existing = collect($items)->where('id', $address->id)->first();

                                    if(!$existing){
                                        $address->delete();
                                    }
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
                                    'account_id'    => $this->getRecord()->account_id,
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
                        ->validationAttribute('ZIP Code')
                        ->disabled(fn(Closure $get) => !$get('address_type') )
                        ->numeric()
                        ->minLength(4)
                        ->maxLength(5)
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Livewire $livewire, TextInput $component, Closure $get, $state){
                            $livewire->validateOnly($component->getStatePath());
                            $this->autoSaveAddress($get('id'), 'zip_code', $state);
                        }),
                    TextInput::make('phone_number')
                        ->label('Phone Number')
                        //->required()
                        //->disabled(fn(Closure $get) => !$get('address_type') )
                        ->label('Phone at Location:')
                        ->default('')
                        ->mask(fn (Mask $mask) => $mask->pattern('(000) 000-0000'))
                        ->rules(['doesnt_start_with:1'])
                        ->validationAttribute('Phone Number')
                        ->lazy()
                        ->afterStateHydrated(function(Closure $get,Closure $set, $state){
                            if(empty($state)){
                                $set('phone_number', '');
                            }
                        })
                        ->afterStateUpdated(function(Closure $get, $state){
                            if($get('id')){
                                $this->autoSaveAddress($get('id'), 'phone_number', $state);
                            }
                        })
                ])
                ->createItemButtonLabel(fn(Closure $get) => count($get('addresses')) ? 'Add Another Address' : 'Add Address')
        ];
    }

    private function autoSaveAddress($id, $column, $value)
    {
        $address = Address::find($id);
        $this->__autoSave($address, $column, $value);
        
        // $address->$column = $value;
        // $address->save();
    }

}