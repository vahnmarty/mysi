<?php

namespace App\Http\Livewire;

use Closure;
use Livewire\Component;
use App\Enums\AddressType;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class SampleForm extends Component implements HasForms
{
    use InteractsWithForms;
    
    public $address_type, $state;
    
    public function render()
    {
        return view('livewire.sample-form');
    }

    protected function getFormSchema()
    {
        return [
            Select::make('address_type')
                ->options(AddressType::asSameArray())
                ->required()
                ->reactive(),
            Select::make('state')
                ->options([
                    'Florida' => 'Florida'
                ])
                ->disabled(fn(Closure $get): bool => $get('address_type') ? false : true )
                ->searchable(fn (Select $component) => !$component->isDisabled())
                ->required()
                ->reactive()
                ->afterStateUpdated(function(Closure $get, $state){
                    
                }),
        ];
    }
}
