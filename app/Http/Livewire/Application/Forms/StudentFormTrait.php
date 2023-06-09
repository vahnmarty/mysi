<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\School;
use App\Enums\RacialType;
use App\Rules\PhoneNumberRule;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
//use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;
use CoringaWc\FilamentInputLoading\TextInput;
use Filament\Forms\Components\TextInput\Mask;

trait StudentFormTrait{

    public function getStudentForm()
    {
        return [
            TextInput::make('student.first_name')
                ->label('Legal First Name')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('first_name', $state);
                }),
            TextInput::make('student.middle_name')
                ->label('Legal Middle Name')
                ->lazy()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('middle_name', $state);
                }),
            TextInput::make('student.last_name')
                ->label('Legal Last Name')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('last_name', $state);
                }),
            Select::make('student.suffix')
                ->options(Suffix::asSameArray())
                ->label('Suffix')
                ->lazy()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('suffix', $state);
                }),
            TextInput::make('student.preferred_first_name')
                ->label('Preferred First Name')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('preferred_first_name', $state);
                }),
            DatePicker::make('student.birthdate')
                ->label('Date of Birth')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('birthdate', $state);
                }),
            Select::make('student.gender')
                ->label('Gender')
                ->options(Gender::asSelectArray())
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('gender', $state);
                }),
            TextInput::make('student.personal_email')
                ->email()
                ->rules(['email:rfc,dns'])
                ->label('Personal Email')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('personal_email', $state);
                }),
            TextInput::make('student.mobile_phone')
                ->label('Mobile Phone')
                ->mask(fn (Mask $mask) => $mask->pattern('(000) 000-0000'))
                ->rules([new PhoneNumberRule])
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('mobile_phone', $state);
                }),
            CheckboxList::make('student.race')
                ->label(new HtmlString('<legend>How do you identify racially?</legend><div class="text-xs">*Select all that apply to you</div>'))
                ->options(RacialType::asSameArray())
                ->columns(3)
                ->lazy()
                ->afterStateHydrated(function (CheckboxList $component, $state) {
                    if(is_string($state)){
                        $component->state(explode(',', $state));
                    }else{
                        $data = is_array($state) ? $state : [];
                        $component->state($data);
                    }
                })
                ->afterStateUpdated(function(Closure $get, $state){

                    $input = is_array($state) ? implode(',', $state) : $state;

                    $this->autoSaveStudent('race', $input);

                    $multi_racial_flag = count($get('student.race')) > 1;

                    $this->autoSaveStudent('multi_racial_flag', $multi_racial_flag);
                }),
            TagsInput::make('student.ethnicity')
                ->label(new HtmlString('<legend>What is your ethnicity?</legend><div class="text-xs">*If more than one, separate ethnicities with a comma.</div>'))
                ->helperText('EXAMPLE: "Filipino, Hawaiian, Irish, Italian, Eritrean, Armenian, Salvadorian"')
                ->lazy()
                ->placeholder('Enter ethnicity then Press Comma or Enter')
                ->afterStateHydrated(function (TagsInput $component, $state) {
                    if(is_string($state)){
                        $component->state(explode(',', $state));
                    }else{
                        $data = is_array($state) ? $state : [];
                        $component->state($data);
                    }
                })
                ->afterStateUpdated(function(Closure $get, $state){
                    $input = is_array($state) ? implode(',', $state) : $state;
                    $this->autoSaveStudent('ethnicity', $input);
                }),
            Select::make('student.current_school')
                ->label('Current School')
                ->options(School::active()->get()->pluck('name', 'name')->toArray() + ['Not Listed' => 'Not Listed'])
                ->preload()
                ->searchable()
                ->reactive()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('current_school', $state);
                }),
            TextInput::make('student.current_school_not_listed')
                ->label('If not listed, add it here')
                ->lazy()
                ->required()
                ->placeholder('Enter School Name')
                ->hidden(fn (Closure $get) => $get('student.current_school') !== self::NotListed)
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('current_school_not_listed', $state);
                }),
            Select::make('other_high_school_1')
                ->label('Other High School #1')
                ->options(School::active()->get()->pluck('name', 'name')->toArray() + ['Not Listed' => 'Not Listed'])
                ->preload()
                ->searchable()
                ->hint('(where you plan to apply)')
                ->placeholder('Enter School Name')
                ->lazy()
                ->afterStateUpdated(function($state){
                    $this->autoSave('other_high_school_1', $state);
                }),
            Select::make('other_high_school_2')
                ->label('Other High School #2')
                ->options(School::active()->get()->pluck('name', 'name')->toArray() + ['Not Listed' => 'Not Listed'])
                ->preload()
                ->searchable()
                ->hint('(where you plan to apply)')
                ->placeholder('Enter School Name')
                ->lazy()
                ->afterStateUpdated(function($state){
                    $this->autoSave('other_high_school_2', $state);
                }),
            Select::make('other_high_school_3')
                ->label('Other High School #3')
                ->options(School::active()->get()->pluck('name', 'name')->toArray() + ['Not Listed' => 'Not Listed'])
                ->preload()
                ->searchable()
                ->hint('(where you plan to apply)')
                ->placeholder('Enter School Name')
                ->lazy()
                ->afterStateUpdated(function($state){
                    $this->autoSave('other_high_school_3', $state);
                }),
            Select::make('other_high_school_4')
                ->label('Other High School #4')
                ->options(School::active()->get()->pluck('name', 'name')->toArray() + ['Not Listed' => 'Not Listed'])
                ->preload()
                ->searchable()
                ->hint('(where you plan to apply)')
                ->placeholder('Enter School Name')
                ->lazy()
                ->afterStateUpdated(function($state){
                    $this->autoSave('other_high_school_4', $state);
                }),
        ];
    }

    private function autoSaveStudent($column, $value)
    {
        $this->__autoSave($this->app->student, $column, $value);
    }
}