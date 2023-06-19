<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Models\Child;
use App\Enums\ParentType;
use App\Enums\SiblingType;
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
            TableRepeater::make('parents')
                ->label('')
                ->disableItemCreation()
                ->disableItemDeletion()
                ->disableItemMovement()
                ->hideLabels()
                ->columnSpan('full')
                ->schema([
                    Hidden::make('id')->reactive(),
                    Hidden::make('first_name')->reactive(),
                    Hidden::make('last_name')->reactive(),
                    TextInput::make('full_name')
                        ->afterStateHydrated(function(Closure $get, Closure $set){
                            $set('full_name', $get('first_name') . ' ' . $get('last_name'));
                        })
                        ->reactive()
                        ->disabled()
                        ->disableLabel(),
                    Select::make('relationship_type')
                        ->disableLabel()
                        ->options(ParentType::asSameArray())
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'), 'relationship_type', $state);
                        }),
                    Select::make('address_location')
                        ->disableLabel()
                        ->options(AddressLocation::asSameArray())
                        ->reactive()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'), 'address_location', $state);
                        })
                        ->required(),
                    Select::make('living_situation')
                        ->disableLabel()
                        ->options(LivingSituationType::asSameArray())
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'), 'living_situation', $state);
                        }),
                    Toggle::make('status')
                        ->label('Deceased?'),
                ]),
            TableRepeater::make('siblings')
                ->label('')
                ->disableItemCreation()
                ->disableItemDeletion()
                ->disableItemMovement()
                ->hideLabels()
                ->columnSpan('full')
                ->schema([
                    Hidden::make('id')->reactive(),
                    Hidden::make('first_name')->reactive(),
                    Hidden::make('last_name')->reactive(),
                    TextInput::make('full_name')
                        ->afterStateHydrated(function(Closure $get, Closure $set){
                            $set('full_name', $get('first_name') . ' ' . $get('last_name'));
                        })
                        ->reactive()
                        ->disabled()
                        ->disableLabel(),
                    Select::make('relationship_type')
                        ->disableLabel()
                        ->options(SiblingType::asSameArray())
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveSibling($get('id'), 'relationship_type', $state);
                        }),
                    Select::make('address_location')
                        ->disableLabel()
                        ->options(AddressLocation::asSameArray())
                        ->reactive()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveSibling($get('id'), 'address_location', $state);
                        })
                        ->required(),
                    Select::make('living_situation')
                        ->disableLabel()
                        ->options(LivingSituationType::asSameArray())
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveSibling($get('id'), 'living_situation', $state);
                        }),
                    Toggle::make('status')
                        ->disabled()
                        ->label('Deceased?'),
                ])
        ];
    }

    private function autoSaveMatrix($id, $column, $value)
    {
        $model = FamilyMatrix::find($id);
        $model->$column = $value;
        $model->save();
    }
}