<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Models\Child;
use App\Models\Address;
use App\Enums\ParentType;
use App\Enums\SiblingType;
use App\Models\FamilyMatrix;
use App\Enums\AddressLocation;
use App\Enums\LivingSituationType;
use Illuminate\Support\HtmlString;
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
            TableRepeater::make('parents_matrix')
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
                        ->label('Parent/Guardian')
                        ->afterStateHydrated(function(Closure $get, Closure $set){
                            $set('full_name', $get('first_name') . ' ' . $get('last_name'));
                        })
                        ->reactive()
                        ->disabled()
                        ->disableLabel(),
                    Select::make('relationship_type')
                        ->label('Relationship to Applicant')
                        ->disableLabel()
                        ->options(ParentType::asSameArray())
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'), 'relationship_type', $state);
                        }),
                    Select::make('address_location')
                        ->label('Address Location')
                        ->disableLabel()
                        ->options(function(){
                            return Address::where('account_id', accountId())->pluck('address_type', 'address_type')->toArray();
                        })
                        ->reactive()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'), 'address_location', $state);
                        })
                        ->required(),
                    Select::make('living_situation')
                        ->label('Living Situation')
                        ->disableLabel()
                        ->options(LivingSituationType::asSameArray())
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'), 'living_situation', $state);
                        }),
                    Toggle::make('status')
                        ->label('Deceased?'),
                    Toggle::make('is_primary')
                        ->label('Primary?')
                        ->reactive()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveParent($get('id'), 'is_primary', $state);
                        }),
                ]),
            TableRepeater::make('siblings_matrix')
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
                        ->label('Sibling')
                        ->afterStateHydrated(function(Closure $get, Closure $set){
                            $set('full_name', $get('first_name') . ' ' . $get('last_name'));
                        })
                        ->reactive()
                        ->disabled()
                        ->disableLabel(),
                    Select::make('relationship_type')
                        ->label('Relationship to Applicant')
                        ->disableLabel()
                        ->options(SiblingType::asSameArray())
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveSibling($get('id'), 'relationship_type', $state);
                        }),
                    Select::make('address_location')
                        ->label('Address Location')
                        ->disableLabel()
                        ->options(function(){
                            return Address::where('account_id', accountId())->pluck('address_type', 'address_type')->toArray();
                        })
                        ->reactive()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveSibling($get('id'), 'address_location', $state);
                        })
                        ->required(),
                    Select::make('living_situation')
                        ->label('Living Situation')
                        ->disableLabel()
                        ->options(LivingSituationType::asSameArray())
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveSibling($get('id'), 'living_situation', $state);
                        }),
                    Toggle::make('status')
                        ->disabled()
                        ->label(new HtmlString('<span class="invisible">Deceased?</span>')),
                    Toggle::make('primary')
                        ->disabled()
                        ->label(new HtmlString('<span class="invisible">Primary?</span>')),
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