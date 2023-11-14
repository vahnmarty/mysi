<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\School;
use App\Enums\RacialType;
use App\Rules\PhoneNumberRule;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Select;
//use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\CheckboxList;
use CoringaWc\FilamentInputLoading\TextInput;
use Filament\Forms\Components\TextInput\Mask;

trait StudentFormTrait{

    public function getStudentForm()
    {
        return [
            Placeholder::make('student_form_description')
                ->label('')
                ->content(new HtmlString('* This section is to be completed by a parent/guardian.')),
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
                ->label('Preferred First Name (Must be different from Legal First Name)')
                ->lazy()
                ->rules([
                    function () {
                        return function (string $attribute, $value, Closure $fail) {
                            if ($value === $this->data['student']['first_name']) {
                                $fail("Legal First Name is the same as Preferred First Name.  Please delete Preferred First Name.");
                            }
                        };
                    },
                ])
                ->afterStateUpdated(function(Livewire $livewire, TextInput $component, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveStudent('preferred_first_name', $state);
                }),
            DatePicker::make('student.birthdate')
                ->label('Date of Birth')
                ->lazy()
                ->required()
                ->closeOnDateSelection()
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
                ->rules([new PhoneNumberRule, 'doesnt_start_with:1'])
                ->validationAttribute('Phone Number')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('mobile_phone', $state);
                }),
            CheckboxList::make('student.race')
                ->label(new HtmlString('<div>How do you identify racially?</div><div class="text-xs" style="font-weight: 500">*Select all that apply to you.</div>'))
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
            Grid::make(2)
                ->schema([
                    Select::make('student.current_school')
                        ->label('Current School')
                        ->options(['Not Listed' => 'Not Listed'] + School::middleSchool()->orderBy('name')->get()->pluck('name', 'name')->toArray())
                        ->preload()
                        ->optionsLimit(50)
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search) => School::search($search)->middleSchool()->orderBy('name')->get()->take(50)->pluck('name', 'name'))
                        ->reactive()
                        ->required()
                        ->afterStateUpdated(function($state){
                            $this->autoSaveStudent('current_school', $state);
                        }),
                    TextInput::make('student.current_school_not_listed')
                        ->label('If not listed, add it here')
                        ->lazy()
                        ->required(fn (Closure $get) => $get('student.current_school') === self::NotListed)
                        ->disabled(fn (Closure $get) => $get('student.current_school') !== self::NotListed)
                        ->afterStateUpdated(function($state){
                            $this->autoSaveStudent('current_school_not_listed', $state);
                        }),
                    Select::make('other_high_school_1')
                        ->label('Other High School #1')
                        ->options(['Not Listed' => 'Not Listed'] + School::highSchool()->notSi()->orderBy('name')->get()->pluck('name', 'name')->toArray() )
                        ->preload()
                        ->optionsLimit(50)
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search) => School::search($search)->highSchool()->notSi()->orderBy('name')->get()->take(50)->pluck('name', 'name'))
                        ->hint('(where you plan to apply)')
                        ->lazy()
                        ->afterStateUpdated(function($state){
                            $this->autoSave('other_high_school_1', $state);
                        }),
                    TextInput::make('other_high_school_1_not_listed')
                        ->label('If not listed, add it here')
                        ->lazy()
                        ->disabled(fn (Closure $get) => $get('other_high_school_1') !== self::NotListed)
                        ->afterStateUpdated(function($state){
                            $this->autoSave('other_high_school_1_not_listed', $state);
                        }),
                    Select::make('other_high_school_2')
                        ->label('Other High School #2')
                        ->options(['Not Listed' => 'Not Listed'] + School::highSchool()->notSi()->orderBy('name')->get()->pluck('name', 'name')->toArray() )
                        ->preload()
                        ->optionsLimit(50)
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search) => School::search($search)->highSchool()->notSi()->orderBy('name')->get()->take(50)->pluck('name', 'name'))
                        ->hint('(where you plan to apply)')
                        ->lazy()
                        ->afterStateUpdated(function($state){
                            $this->autoSave('other_high_school_2', $state);
                        }),
                    TextInput::make('other_high_school_2_not_listed')
                        ->label('If not listed, add it here')
                        ->lazy()
                        ->disabled(fn (Closure $get) => $get('other_high_school_2') !== self::NotListed)
                        ->afterStateUpdated(function($state){
                            $this->autoSave('other_high_school_2_not_listed', $state);
                        }),
                    Select::make('other_high_school_3')
                        ->label('Other High School #3')
                        ->options(['Not Listed' => 'Not Listed'] + School::highSchool()->notSi()->orderBy('name')->get()->pluck('name', 'name')->toArray() )
                        ->preload()
                        ->optionsLimit(50)
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search) => School::search($search)->highSchool()->notSi()->orderBy('name')->get()->take(50)->pluck('name', 'name'))
                        ->hint('(where you plan to apply)')
                        ->lazy()
                        ->afterStateUpdated(function($state){
                            $this->autoSave('other_high_school_3', $state);
                        }),
                    TextInput::make('other_high_school_3_not_listed')
                        ->label('If not listed, add it here')
                        ->lazy()
                        ->disabled(fn (Closure $get) => $get('other_high_school_3') !== self::NotListed)
                        ->afterStateUpdated(function($state){
                            $this->autoSave('other_high_school_3_not_listed', $state);
                        }),
                    Select::make('other_high_school_4')
                        ->label('Other High School #4')
                        ->options(['Not Listed' => 'Not Listed'] + School::highSchool()->notSi()->orderBy('name')->get()->pluck('name', 'name')->toArray() )
                        ->preload()
                        ->optionsLimit(50)
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search) => School::search($search)->highSchool()->notSi()->orderBy('name')->get()->take(50)->pluck('name', 'name'))
                        ->hint('(where you plan to apply)')
                        ->lazy()
                        ->afterStateUpdated(function($state){
                            $this->autoSave('other_high_school_4', $state);
                        }),
                    TextInput::make('other_high_school_4_not_listed')
                        ->label('If not listed, add it here')
                        ->lazy()
                        ->disabled(fn (Closure $get) => $get('other_high_school_4') !== self::NotListed)
                        ->afterStateUpdated(function($state){
                            $this->autoSave('other_high_school_4_not_listed', $state);
                        }),
                ]),
            
        ];
    }

    private function autoSaveStudent($column, $value)
    {
        $this->__autoSave($this->app->student, $column, $value);
    }
}