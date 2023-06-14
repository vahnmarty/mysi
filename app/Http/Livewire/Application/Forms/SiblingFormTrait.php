<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Enums\Gender;
use App\Models\School;
use App\Enums\RacialType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
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
                TextInput::make('first_name')
                    ->label('Legal First Name')
                    ->lazy()
                    ->required()
                    ->afterStateUpdated(function($state){
                        $this->autoSaveSibling('first_name', $state, self::ChildModel);
                    }),
                TextInput::make('last_name')
                    ->label('Legal Last Name')
                    ->lazy()
                    ->required()
                    ->afterStateUpdated(function($state){
                        $this->autoSaveSibling('last_name', $state, self::ChildModel);
                    }),
                TextInput::make('middle_name')
                    ->label('Legal Middle Name')
                    ->lazy()
                    ->required()
                    ->afterStateUpdated(function($state){
                        $this->autoSaveSibling('middle_name', $state, self::ChildModel);
                    }),
                TextInput::make('suffix')
                    ->label('Suffix')
                    ->lazy()
                    ->required()
                    ->afterStateUpdated(function($state){
                        $this->autoSaveSibling('suffix', $state, self::ChildModel);
                    }),
                TextInput::make('preferred_first_name')
                    ->label('Preferred First Name')
                    ->lazy()
                    ->required()
                    ->afterStateUpdated(function($state){
                        $this->autoSaveSibling('preferred_first_name', $state, self::ChildModel);
                    }),
                TextInput::make('personal_email')
                    ->label('Personal Email')
                    ->lazy()
                    ->required()
                    ->afterStateUpdated(function($state){
                        $this->autoSaveSibling('personal_email', $state, self::ChildModel);
                    }),
                
                Select::make('current_school')
                    ->label('Current School')
                    ->options(School::active()->get()->pluck('name', 'name')->toArray() + ['Not Listed' => 'Not Listed'])
                    ->preload()
                    ->searchable()
                    ->lazy()
                    ->required()
                    ->afterStateUpdated(function($state){
                        $this->autoSaveSibling('current_school', $state, self::ChildModel);
                    }),
                TextInput::make('current_school_not_listed')
                    ->label('If not listed, add it here')
                    ->lazy()
                    ->required()
                    ->placeholder('Enter School Name')
                    ->hidden(fn (Closure $get) => $get('current_school') !== self::NotListed)
                    ->afterStateUpdated(function($state){
                        //$this->autoSaveSibling('current_school_not_listed', $state, self::ChildModel);
                    }),
            ])
            
        ];
    }
}