<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Models\Child;
use App\Enums\ParentType;
use App\Models\FamilyMatrix;
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
                TextInput::make('full_name')
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
                    ->reactive()
                    ->afterStateUpdated(function(Closure $get, $state){
                        $this->autoSaveMatrix($get('id'), 'address_location', $state);
                    })
                    ->required(),
                Select::make('living_situation')
                    ->disableLabel()
                    ->options(LivingSituationType::asSameArray())
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function(Closure $get, $state){
                        $this->autoSaveMatrix($get('id'), 'living_situation', $state);
                    }),
                Toggle::make('status')
                    ->label('Deceased?'),
            ])
            ->columnSpan('full')
        ];
    }

    private function autoSaveMatrix($id, $column, $value)
    {
        $model = FamilyMatrix::find($id);
        $model->$column = $value;
        $model->save();
    }
}