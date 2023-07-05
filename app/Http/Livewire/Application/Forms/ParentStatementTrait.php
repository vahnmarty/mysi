<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\School;
use App\Enums\ParentType;
use App\Enums\RacialType;
use App\Enums\CommonOption;
use App\Enums\ReligionType;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Radio;
use App\Enums\FamilySpiritualityType;
use Filament\Forms\Components\Select;
//use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;


use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput\Mask;
use Wiebenieuwenhuis\FilamentCharCounter\Textarea;

trait ParentStatementTrait{

    public function getParentStatement()
    {
        return [
            Placeholder::make('section_parent_statement')
                ->label('')
                ->content(new HtmlString('*This section is to be completed by a parent/guardian only.')),
            Textarea::make('why_si_for_your_child')
                ->label("Why do you want your child to attend St. Ignatius College Preparatory?")
                ->helperText("Up to 500 characters only.")
                ->lazy()
                ->required()
                ->rows(5)
                ->maxLength(500)
                ->afterStateUpdated(function($state){
                    $this->autoSave('why_si_for_your_child', $state);
                }),
            Textarea::make('childs_quality_and_area_of_growth')
                ->label("Explain your child's most endearing quality and an area of growth.")
                ->helperText("Up to 500 characters only.")
                ->lazy()
                ->required()
                ->rows(5)
                ->maxLength(500)
                ->afterStateUpdated(function($state){
                    $this->autoSave('childs_quality_and_area_of_growth', $state);
                }),
            Textarea::make('something_about_child')
                ->label("Please tell us something about your child that does not appear on this application.")
                ->helperText("Up to 500 characters only.")
                ->lazy()
                ->required()
                ->rows(5)
                ->maxLength(500)
                ->afterStateUpdated(function($state){
                    $this->autoSave('something_about_child', $state);
                }),
            TextInput::make('parent_statement_by')
                    ->label('Parent/Guardian Statement Submitted By')
                    ->required()
                    ->lazy()
                    ->afterStateUpdated(function($state){
                        $this->autoSave('parent_statement_by', $state);
                    }),
            Select::make('parent_relationship_to_student')
                ->label('Relationship to Applicant')
                ->options(ParentType::asSameArray())
                ->required()
                ->lazy()
                ->afterStateUpdated(function($state){
                    $this->autoSave('parent_relationship_to_student', $state);
                }),
        ];
    }
}