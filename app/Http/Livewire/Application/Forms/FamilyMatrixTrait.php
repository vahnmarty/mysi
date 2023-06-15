<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Enums\ParentType;
use App\Enums\AddressLocation;
use App\Enums\LivingSituationType;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;


trait FamilyMatrixTrait{

    public function getFamilyMatrix()
    {
        return [
            TableRepeater::make('matrix')
            ->label('')
            ->disableItemCreation()
            ->disableItemDeletion()
            ->disableItemMovement()
            ->hideLabels()
            ->schema([
                Hidden::make('id')->reactive(),
                Hidden::make('first_name')->reactive(),
                Hidden::make('last_name')->reactive(),
                TextInput::make('name')
                    ->formatStateUsing(function(Closure $get){
                        return $get('first_name') . ' ' .$get('last_name');
                    })
                    ->reactive()
                    ->disabled()
                    ->disableLabel(),
                Select::make('relationship')
                    ->disableLabel()
                    ->options(ParentType::asSameArray())
                    ->required()
                    ->lazy()
                    ->afterStateUpdated(function(Closure $get, $state){
                        $this->autoSaveMatrix($get('id'), 'relationship', $state);
                    }),
                Select::make('address_location')
                    ->disableLabel()
                    ->options(AddressLocation::asSameArray())
                    ->required(),
                Select::make('living_situation')
                    ->disableLabel()
                    ->options(LivingSituationType::asSameArray())
                    ->required(),
                Toggle::make('status')
                    ->label('Deceased?'),
            ])
            ->columnSpan('full')
        ];
    }

    private function autoSaveMatrix($id, $column, $value)
    {
        
    }
}