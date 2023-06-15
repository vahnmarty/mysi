<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\Child;
use App\Models\School;
use App\Enums\RacialType;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;

trait SiblingFormTrait{

    public function getSiblingForm()
    {
        return [
            Repeater::make('siblings')
            ->createItemButtonLabel('Add Children')
            ->defaultItems(1)
            ->schema([
                Hidden::make('id')
                    ->afterStateHydrated(function(Hidden $component, Closure $set, Closure $get, $state){
                        if(!$state){
                            $child = Child::create(['account_id' => $this->app->account_id]);
                            $set('id', $child->id);
                        }
                    }),
                TextInput::make('first_name')
                    ->label('Legal First Name')
                    ->lazy()
                    ->required()
                    ->afterStateUpdated(function(Closure $get, $state){
                        $this->autoSaveSibling($get('id'), 'first_name', $state);
                    }),
                TextInput::make('last_name')
                    ->label('Legal Last Name')
                    ->lazy()
                    ->required()
                    ->afterStateUpdated(function(Closure $get, $state){
                        $this->autoSaveSibling($get('id'), 'last_name', $state);
                    }),
                TextInput::make('middle_name')
                    ->label('Legal Middle Name')
                    ->lazy()
                    ->required()
                    ->afterStateUpdated(function(Closure $get, $state){
                        $this->autoSaveSibling($get('id'), 'middle_name', $state);
                    }),
                    Select::make('suffix')
                    ->options(Suffix::asSelectArray())
                    ->label('Suffix')
                    ->lazy()
                    ->required()
                    ->afterStateUpdated(function(Closure $get, $state){
                        $this->autoSaveSibling($get('id'), 'suffix', $state);
                    }),
                TextInput::make('preferred_first_name')
                    ->label('Preferred First Name')
                    ->lazy()
                    ->required()
                    ->afterStateUpdated(function(Closure $get, $state){
                        $this->autoSaveSibling($get('id'), 'preferred_first_name', $state);
                    }),
                TextInput::make('personal_email')
                    ->label('Personal Email')
                    ->lazy()
                    ->required()
                    ->email()
                    ->afterStateUpdated(function(Closure $get, $state){
                        $this->autoSaveSibling($get('id'), 'personal_email', $state);
                    }),
                Select::make('current_school')
                    ->label('Current School')
                    ->options(School::active()->get()->pluck('name', 'name')->toArray() + ['Not Listed' => 'Not Listed'])
                    ->preload()
                    ->searchable()
                    ->lazy()
                    ->required()
                    ->afterStateUpdated(function(Closure $get, $state){
                        $this->autoSaveSibling($get('id'), 'current_school', $state);
                    }),
                TextInput::make('current_school_not_listed')
                    ->label('If not listed, add it here')
                    ->lazy()
                    ->required()
                    ->placeholder('Enter School Name')
                    ->hidden(fn (Closure $get) => $get('current_school') !== self::NotListed)
                    ->afterStateUpdated(function(Closure $get, $state){
                        $this->autoSaveSibling($get('id'), 'current_school_not_listed', $state);
                    }),
            ])
            ->registerListeners([
                'repeater::deleteItem' => [
                    function (Component $component, string $statePath, string $uuidToDelete): void {
                        $items = $component->getState();
                        $siblings = Child::where('account_id', $this->app->account_id)->where('id', '!=', $this->app->child_id)->get();

                        foreach($siblings as $index => $child){
                            $existing = collect($items)->where('id', $child->id)->first();

                            if(!$existing){
                                $child->delete();
                            }
                        }
                    },
                ],
            ])
            
        ];
    }

    private function autoSaveSibling($id, $column, $value)
    {
        $model = Child::find($id);
        $model->$column = $value;
        $model->save();
    }

    public function syncMatrix(Closure $get)
    {
        
    }
}