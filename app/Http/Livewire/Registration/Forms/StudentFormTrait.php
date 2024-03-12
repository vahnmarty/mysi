<?php

namespace App\Http\Livewire\Registration\Forms;

use Closure;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\School;
use App\Enums\ShirtSize;
use App\Enums\RacialType;
use App\Enums\ReligionType;
use App\Enums\ArtProgramsType;
use App\Rules\PhoneNumberRule;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput\Mask;

trait StudentFormTrait{

    public function getStudentForm()
    {
        return [
            Placeholder::make('student_form_description')
                ->label('')
                ->content(new HtmlString('<p>* This section is to be completed by a parent/guardian.</p>')),
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
                ->label('Personal Email (If none, use a parent\'s/guardian\'s email address.)')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('personal_email', $state);
                }),
            TextInput::make('student.mobile_phone')
                ->label('Mobile Phone (If none, use a parent\'s/guardian\'s mobile phone.)')
                ->mask(fn (Mask $mask) => $mask->pattern('(000) 000-0000'))
                ->rules([new PhoneNumberRule, 'doesnt_start_with:1'])
                ->validationAttribute('Phone Number')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('mobile_phone', $state);
                }),
            CheckboxList::make('student.race')
                ->label(new HtmlString('
                    <div>
                        How does your child identify racially?
                    </div>
                    <div class="text-sm" style="font-weight: 500">* Select all that apply to you.</div>
                '))
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
                ->label(new HtmlString('<div>What is your child\'s ethnicity?</div>
                    <div class="text-sm" style="font-weight: 500">* If more than one, separate ethnicities with a comma.</div>'))
                ->helperText('EXAMPLE: "Filipino, Hawaiian, Irish, Italian, Eritrean, Armenian, Salvadorian"')
                ->lazy()
                ->placeholder('')
                ->afterStateHydrated(function (TagsInput $component, $state) {
                    if(is_string($state)){
                        $newState = removeQuotes($state);
                        $array = explode(',', $newState);
                        $component->state($array);
                    }else{
                        $data = is_array($state) ? $state : [];
                        $component->state($data);
                    }
                })
                ->afterStateUpdated(function(Closure $get, $state){
                    $input = is_array($state) ? implode(',', $state) : $state;
                    $this->autoSaveStudent('ethnicity', $input);
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
                ]),
            // Select::make('student.tshirt_size')
            //     ->options(ShirtSize::asSameArray())
            //     ->label('T-Shirt Size (Adult/Unisex)')
            //     ->lazy()
            //     ->afterStateUpdated(function($state){
            //         $this->autoSaveStudent('tshirt_size', $state);
            //     }),
            // Select::make('student.performing_arts_flag')
            //     ->options([
            //         1 => 'Yes',
            //         0 => 'No'
            //     ])
            //     ->label('Would you be interested in joining any of our performing arts programs?')
            //     ->required()
            //     ->lazy()
            //     ->afterStateUpdated(function($state){
            //         $this->autoSaveStudent('performing_arts_flag', $state);
            //     }),
            // Select::make('student.performing_arts_programs')
            //     ->multiple()
            //     ->options(ArtProgramsType::asSameArray() + ['Other' => 'Other'])
            //     ->required()
            //     ->label('Please select all the programs you are interested in')
            //     ->lazy()
            //     ->visible(fn (Closure $get) => $get('student.performing_arts_flag') == true)
            //     ->afterStateHydrated(function (Select $component, $state) {
            //         if(is_string($state)){
            //             $component->state(explode(',', $state));
            //         }else{
            //             $data = is_array($state) ? $state : [];
            //             $component->state($data);
            //         }
            //     })
            //     ->afterStateUpdated(function(Closure $get, $state){
            //         $input = is_array($state) ? implode(',', $state) : $state;
            //         $this->autoSaveStudent('performing_arts_programs', $input);
            //     }),
            // TextInput::make('student.performing_arts_other')
            //     ->label('If Other, Please specify:')
            //     ->lazy()
            //     ->required()
            //     ->visible(fn (Closure $get) => in_array('Other', $get('student.performing_arts_programs')) )
            //     ->afterStateUpdated(function($state){
            //         $this->autoSaveStudent('performing_arts_other', $state);
            //     }),
            Select::make('student.religion')
                ->options(ReligionType::asSelectArray())
                ->label("Student's Religion")
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('religion', $state);
                }),
            TextInput::make('student.religion_other')
                ->label('If "Other," add it here')
                ->lazy()
                ->required()
                ->placeholder('Enter Religion')
                ->hidden(fn (Closure $get) => $get('student.religion') !== ReligionType::Other)
                ->afterStateUpdated(function(Closure $get, $state){
                    $this->autoSaveStudent('religion_other', $state);
                }),
            TextInput::make('student.religious_community')
                ->label('Church/Faith Community')
                ->lazy()
                ->afterStateUpdated(function(Closure $get, $state){
                    $this->autoSaveStudent('religious_community', $state);
                }),
            TextInput::make('student.religious_community_location')
                ->label('Church/Faith Community Location')
                ->lazy()
                ->afterStateUpdated(function(Closure $get, $state){
                    $this->autoSaveStudent('religious_community_location', $state);
                }),
            TextInput::make('student.baptism_year')
                ->label('Baptism Year')
                ->integer()
                ->minLength(4)
                ->maxLength(4)
                //->maxValue(date('Y'))
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('0000'))
                ->lazy()
                ->afterStateUpdated(function(Livewire $livewire, Closure $get, $state){
                    $livewire->validateOnly('data.student.baptism_year');
                    $this->autoSaveStudent('baptism_year', $state);
                }),
            TextInput::make('student.confirmation_year')
                ->label('Confirmation Year')
                ->numeric()
                ->integer()
                ->minLength(4)
                ->maxLength(4)
                //->maxValue(date('Y'))
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('0000'))
                ->lazy()
                ->afterStateUpdated(function(Livewire $livewire, Closure $get, $state){
                    $livewire->validateOnly('data.student.confirmation_year');
                    $this->autoSaveStudent('confirmation_year', $state);
                }),
            
        ];
    }

    private function autoSaveStudent($column, $value)
    {
        $this->__autoSave($this->registration->student, $column, $value);
    }
}