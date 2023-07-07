<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;

use Livewire\Component as Livewire;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\School;
use App\Enums\ParentType;
use App\Enums\RacialType;
use App\Enums\CommonOption;
use App\Enums\ReligionType;
use App\Rules\MaxWordCount;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Radio;
use App\Enums\FamilySpiritualityType;
//use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use App\Forms\Components\WordTextArea;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput\Mask;
use Wiebenieuwenhuis\FilamentCharCounter\Textarea;

trait StudentStatementTrait{

    public function getStudentStatement()
    {
        return [
            Placeholder::make('section_student_statement')
                ->label('')
                ->content(new HtmlString('*This section is to be completed by the applicant only.')),
            WordTextArea::make('why_did_you_apply')
                ->label("Why do you want to attend St. Ignatius College Preparatory?")
                ->helperText("Please limit your answer to 75 words.")
                ->lazy()
                ->required()
                ->rows(5)
                ->wordLimit(75)
                ->rules([
                    new MaxWordCount(75)
                ])
                ->afterStateUpdated(function(Livewire $livewire, WordTextArea $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSave('why_did_you_apply', $state);
                }),
            WordTextArea::make('greatest_challenge')
                ->label("What do you think will be your greatest challenge at SI and how do you plan to meet that challenge?")
                ->helperText("Please limit your answer to 75 words.")
                ->lazy()
                ->required()
                ->rows(5)
                ->maxLength(500)
                ->afterStateUpdated(function($state){
                    $this->autoSave('greatest_challenge', $state);
                }),
            WordTextArea::make('religious_activity_participation')
                ->label("What religious activity or activities do you plan on participating in at SI as part of your spiritual growth and why?")
                ->helperText("Please limit your answer to 75 words.")
                ->lazy()
                ->required()
                ->rows(5)
                ->afterStateUpdated(function(Livewire $livewire, WordTextArea $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSave('religious_activity_participation', $state);
                }),
            WordTextArea::make('favorite_and_difficult_subjects')
                ->label("What is your favorite subject in school and why? What subject do you find the most difficult and why?")
                ->helperText("Please limit your answer to 75 words.")
                ->lazy()
                ->required()
                ->rows(5)
                ->afterStateUpdated(function(Livewire $livewire, WordTextArea $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSave('favorite_and_difficult_subjects', $state);
                }),
            
        ];
    }
}