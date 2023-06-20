<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\School;
use Livewire\Component as Livewire;
use App\Enums\ParentType;
use App\Enums\RacialType;
use App\Enums\CommonOption;
use App\Enums\ReligionType;
use Filament\Forms\Components\Radio;
use App\Enums\FamilySpiritualityType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput\Mask;

trait ReligionFormTrait{

    public function getReligionForm()
    {
        return [
            
            Select::make('student.religion')
                ->options(ReligionType::asSelectArray())
                ->label("Applicant(s)'s Religion")
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
                ->numeric()
                ->integer()
                ->minLength(4)
                ->maxLength(4)
                ->maxValue(date('Y'))
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
                ->maxValue(date('Y'))
                ->lazy()
                ->afterStateUpdated(function(Livewire $livewire, Closure $get, $state){
                    $livewire->validateOnly('data.student.confirmation_year');
                    $this->autoSaveStudent('confirmation_year', $state);
                }),
            Textarea::make('impact_to_community')
                ->label("What impact does community have in your life and how do you best support your child's school community?")
                ->helperText("Up to 500 characters only.")
                ->required()
                ->rows(5)
                ->maxLength(500)
                ->afterStateUpdated(function($state){
                    $this->autoSave('impact_to_community', $state);
                }),
            CheckboxList::make('describe_family_spirituality')
                ->label("How would you describe your family's spirituality? ")
                ->options(FamilySpiritualityType::asSameArray())
                ->columns(3)
                ->lazy()
                ->required()
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
                    $this->autoSave('describe_family_spirituality', $input);
                }),
            Textarea::make('describe_family_spirituality_in_detail')
                ->label("What impact does community have in your life and how do you best support your child's school community?")
                ->helperText("Up to 500 characters only.")
                ->required()
                ->rows(5)
                ->maxLength(500)
                ->afterStateUpdated(function($state){
                    $this->autoSave('describe_family_spirituality_in_detail', $state);
                }),
            Fieldset::make('Will you encourage your child to proactively participate in the following activities?')
                ->columns(3)
                ->schema([
                    Select::make('religious_studies_classes')
                        ->options(CommonOption::asSelectArray())
                        ->label("Religious Studies Classes")
                        ->lazy()
                        ->required()
                        ->afterStateUpdated(function($state){
                            $this->autoSave('religious_studies_classes', $state);
                        }),
                    TextInput::make('religious_studies_classes_explanation')
                        ->label('If No/Unsure, please explain')
                        ->columnSpan(2)
                        ->lazy()
                        ->disabled(fn (Closure $get) => empty($get('religious_studies_classes')) || $get('religious_studies_classes') == CommonOption::Yes)
                        ->afterStateUpdated(function($state){
                            $this->autoSave('religious_studies_classes_explanation', $state);
                        }),
                    Select::make('school_liturgies')
                        ->options(CommonOption::asSelectArray())
                        ->label("School Liturgies")
                        ->lazy()
                        ->required()
                        ->afterStateUpdated(function($state){
                            $this->autoSave('school_liturgies', $state);
                        }),
                    TextInput::make('school_liturgies_explanation')
                        ->label('If No/Unsure, please explain')
                        ->columnSpan(2)
                        ->lazy()
                        ->disabled(fn (Closure $get) => empty($get('school_liturgies')) || $get('school_liturgies') == CommonOption::Yes)
                        ->afterStateUpdated(function($state){
                            $this->autoSave('school_liturgies_explanation', $state);
                    }),
                    Select::make('retreats')
                        ->options(CommonOption::asSelectArray())
                        ->label("Retreats")
                        ->lazy()
                        ->required()
                        ->afterStateUpdated(function($state){
                            $this->autoSave('retreats', $state);
                        }),
                    TextInput::make('retreats_explanation')
                        ->label('If No/Unsure, please explain')
                        ->columnSpan(2)
                        ->lazy()
                        ->disabled(fn (Closure $get) => empty($get('retreats')) || $get('retreats') == CommonOption::Yes)
                        ->afterStateUpdated(function($state){
                            $this->autoSave('retreats_explanation', $state);
                    }),
                    Select::make('community_service')
                        ->options(CommonOption::asSelectArray())
                        ->label("Community Service")
                        ->lazy()
                        ->required()
                        ->afterStateUpdated(function($state){
                            $this->autoSave('community_service', $state);
                        }),
                    TextInput::make('community_service_explanation')
                        ->label('If No/Unsure, please explain')
                        ->columnSpan(2)
                        ->lazy()
                        ->disabled(fn (Closure $get) => empty($get('community_service')) || $get('community_service') == CommonOption::Yes)
                        ->afterStateUpdated(function($state){
                            $this->autoSave('community_service_explanation', $state);
                        }),
                ]),

                TextInput::make('religious_statement_by')
                    ->label('Religious Form Submitted By')
                    ->required()
                    ->lazy()
                    ->afterStateUpdated(function($state){
                        $this->autoSave('religious_statement_by', $state);
                    }),
                Select::make('religious_relationship_to_student')
                    ->label('Relationship to Student')
                    ->options(ParentType::asSameArray())
                    ->required()
                    ->lazy()
                    ->afterStateUpdated(function($state){
                        $this->autoSave('religious_relationship_to_student', $state);
                    }),
            
        ];
    }
}