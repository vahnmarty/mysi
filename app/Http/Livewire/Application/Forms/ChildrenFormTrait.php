<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Enums\Gender;
use App\Models\School;
use App\Enums\RacialType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;

trait ChildrenFormTrait{

    public function getChildrenForm()
    {
        return [
            TextInput::make(self::ChildModel .'.first_name')
                ->label('Legal First Name')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('first_name', $state, self::ChildModel);
                }),
            TextInput::make(self::ChildModel .'.last_name')
                ->label('Legal Last Name')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('last_name', $state, self::ChildModel);
                }),
            TextInput::make(self::ChildModel .'.middle_name')
                ->label('Legal Middle Name')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('middle_name', $state, self::ChildModel);
                }),
            TextInput::make(self::ChildModel .'.suffix')
                ->label('Suffix')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('suffix', $state, self::ChildModel);
                }),
            TextInput::make(self::ChildModel .'.preferred_first_name')
                ->label('Preferred First Name')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('preferred_first_name', $state, self::ChildModel);
                }),
            DatePicker::make(self::ChildModel .'.birthdate')
                ->label('Date of Birth')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('birthdate', $state, self::ChildModel);
                }),
            Select::make(self::ChildModel .'.gender')
                ->label('Gender')
                ->options(Gender::asSelectArray())
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('gender', $state, self::ChildModel);
                }),
            TextInput::make(self::ChildModel .'.personal_email')
                ->label('Personal Email')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('personal_email', $state, self::ChildModel);
                }),
            TextInput::make(self::ChildModel .'.mobile_phone')
                ->label('Mobile Phone')
                ->tel()
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('mobile_phone', $state, self::ChildModel);
                }),
            CheckboxList::make(self::ChildModel .'.race')
                ->label('How do you identify racially?')
                ->options(RacialType::asSameArray())
                ->columns(3)
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    //$this->autoSave('race', $state, self::ChildModel);
                }),
            TagsInput::make(self::ChildModel .'.ethnicity')
                ->label('What is your ethnicity?')
                ->helperText('EXAMPLE: "Filipino, Hawaiian, Irish, Italian, Middle Eastern, Salvadorian"')
                ->lazy()
                ->required()
                ->placeholder('Enter ethnicity then Press Comma or Enter')
                ->afterStateUpdated(function($state){
                    $this->autoSave('ethnicity', $state, self::ChildModel);
                }),
            Select::make(self::ChildModel .'.current_school')
                ->label('Current School')
                ->options(School::active()->get()->pluck('name', 'name')->toArray() + ['Not Listed' => 'Not Listed'])
                ->preload()
                ->searchable()
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('current_school', $state, self::ChildModel);
                }),
            TextInput::make(self::ChildModel .'.current_school_not_listed')
                ->label('If not listed, add it here')
                ->lazy()
                ->required()
                ->placeholder('Enter School Name')
                ->hidden(fn (Closure $get) => $get(self::ChildModel .'.current_school') !== self::NotListed)
                ->afterStateUpdated(function($state){
                    //$this->autoSave('current_school_not_listed', $state, self::ChildModel);
                }),
            TextInput::make('other_high_school_1')
                ->label('Other High School #1')
                ->hint('(where you plan to apply)')
                ->placeholder('Enter School Name')
                ->lazy()
                ->afterStateUpdated(function($state){
                    $this->autoSave('other_high_school_1', $state);
                }),
            TextInput::make('other_high_school_2')
                ->label('Other High School #2')
                ->hint('(where you plan to apply)')
                ->placeholder('Enter School Name')
                ->lazy()
                ->afterStateUpdated(function($state){
                    $this->autoSave('other_high_school_2', $state);
                }),
            TextInput::make('other_high_school_3')
                ->label('Other High School #3')
                ->hint('(where you plan to apply)')
                ->placeholder('Enter School Name')
                ->lazy()
                ->afterStateUpdated(function($state){
                    $this->autoSave('other_high_school_3', $state);
                }),
            TextInput::make('other_high_school_4')
                ->label('Other High School #4')
                ->hint('(where you plan to apply)')
                ->placeholder('Enter School Name')
                ->lazy()
                ->afterStateUpdated(function($state){
                    $this->autoSave('other_high_school_4', $state);
                }),
        ];
    }
}