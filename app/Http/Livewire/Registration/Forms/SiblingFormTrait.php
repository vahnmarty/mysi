<?php

namespace App\Http\Livewire\Registration\Forms;

use Closure;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\Child;
use App\Models\School;
use App\Enums\GradeLevel;
use App\Enums\RacialType;
use Illuminate\Support\HtmlString;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\CheckboxList;

trait SiblingFormTrait{

    public function getSiblingForm()
    {
        return [
            Placeholder::make('sibling_form_description')
                ->label('')
                ->content(new HtmlString('* This section is to be completed by a parent/guardian.')),
            Repeater::make('siblings')
                ->label('')
                ->createItemButtonLabel(fn(Closure $get) => count($get('siblings')) ? 'Add Another Sibling' : 'Add Sibling')
                ->disableItemMovement()
                ->maxItems(10)
                ->registerListeners([
                    'repeater::deleteItem' => [
                        function (Component $component, string $statePath, string $uuidToDelete): void {

                            if($statePath == 'data.siblings')
                            {
                                $items = $component->getState();
                                $siblings = Child::where('account_id', $this->getRecord()->account_id)
                                    ->where('id', '!=', $this->getRecord()->child_id)
                                    ->get();

                                foreach($siblings as $index => $child){
                                    $existing = collect($items)->where('id', $child->id)->first();

                                    if(!$existing){
                                        $child->delete();
                                    }
                                }
                            }
                            
                        },
                    ],
                ])
                ->schema([
                    Hidden::make('id')
                        ->afterStateHydrated(function(Hidden $component, Closure $set, Closure $get, $state){
                            if(!$state){
                                $child = Child::create(['account_id' => $this->getRecord()->account_id]);
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
                    TextInput::make('middle_name')
                        ->label('Legal Middle Name')
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveSibling($get('id'), 'middle_name', $state);
                        }),
                    TextInput::make('last_name')
                        ->label('Legal Last Name')
                        ->lazy()
                        ->required()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveSibling($get('id'), 'last_name', $state);
                        }),
                    Select::make('suffix')
                        ->options(Suffix::asSameArray())
                        ->label('Suffix')
                        ->lazy()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveSibling($get('id'), 'suffix', $state);
                        }),
                    // TextInput::make('preferred_first_name')
                    //     ->label('Preferred First Name')
                    //     ->lazy()
                    //     ->required()
                    //     ->afterStateUpdated(function(Closure $get, $state){
                    //         $this->autoSaveSibling($get('id'), 'preferred_first_name', $state);
                    //     }),
                    // TextInput::make('personal_email')
                    //     ->label('Personal Email')
                    //     ->lazy()
                    //     ->required()
                    //     ->email()
                    //     ->afterStateUpdated(function(Livewire $livewire, Closure $get, Component $component, $state){
                    //         $livewire->validateOnly($component->getStatePath());
                    //         $this->autoSaveSibling($get('id'), 'personal_email', $state);
                    //     }),
                    Select::make('gender')
                        ->label('Gender')
                        ->options(Gender::asSelectArray())
                        ->preload()
                        ->lazy()
                        ->required()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveSibling($get('id'), 'gender', $state);
                        }),
                    DatePicker::make('birthdate')
                        ->lazy()
                        ->required()
                        ->closeOnDateSelection()
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveSibling($get('id'), 'birthdate', $state);
                        }),
                    Select::make('current_grade')
                        ->label('Current Grade')
                        ->options(GradeLevel::asSameArray())
                        ->preload()
                        ->lazy()
                        ->required()
                        ->afterStateUpdated(function(Closure $get, $state){
                            
                            $this->autoSaveSibling($get('id'), 'current_grade', $state);

                            $child = Child::find($get('id'));
                            $child->expected_graduation_year = $child->getExpectedGraduationYear();
                            $child->expected_enrollment_year = $child->getExpectedEnrollmentYear();
                            $child->save();
                            
                        }),
                    Select::make('current_school')
                        ->label('Current School')
                        ->options(School::active()->get()->pluck('name', 'name')->toArray() + ['Not Listed' => 'Not Listed'])
                        ->preload()
                        ->optionsLimit(50)
                        ->required(fn(Closure $get) => $get('current_grade') != GradeLevel::PostCollege)
                        ->reactive()
                        ->searchable(fn (Select $component) => !$component->isDisabled())
                        ->getSearchResultsUsing(fn (string $search) => School::search($search)->orderBy('name')->get()->take(50)->pluck('name', 'name'))
                        ->disabled(fn(Closure $get) => $get('current_grade') == GradeLevel::PostCollege)
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
                    
                    Radio::make('graduated_at_si')
                        ->label('Graduated high school from SI?')
                        ->lazy()
                        ->options([
                            1 => 'Yes',
                            0 => 'No',
                        ])
                        ->required()
                        ->visible(fn(Closure $get) => $get('current_grade') == GradeLevel::College || $get('current_grade') == GradeLevel::PostCollege)
                        ->afterStateUpdated(function(Closure $get, $state){
                            $this->autoSaveSibling($get('id'), 'graduated_at_si', $state);
                        }),
                    // TextInput::make('graduation_year')
                    //     ->label('High School Graduation Year')
                    //     ->lazy()
                    //     ->minLength(4)
                    //     ->maxLength(4)
                    //     ->maxValue(2027)
                    //     ->mask(fn (TextInput\Mask $mask) => $mask->pattern('0000'))
                    //     ->afterStateUpdated(function(Livewire $livewire, TextInput $component, Closure $get, $state){
                    //         dd($component->getStatePath(), $livewire->validateOnly($component->getStatePath()));
                    //         $this->autoSaveSibling($get('id'), 'graduation_year', $state);
                    //     }),
                    TextInput::make('graduation_year')
                        ->label('High School Graduation Year')
                        ->validationAttribute('High School Graduation Year')
                        ->lazy()
                        ->numeric()
                        ->minLength(4)
                        ->maxLength(4)
                        //->maxValue(date('Y'))
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('0000'))
                        ->afterStateUpdated(function(Livewire $livewire, Closure $get, TextInput $component, $state){
                            $livewire->validateOnly($component->getStatePath());
                            $this->autoSaveSibling($get('id'), 'graduation_year', $state);
                        }),
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