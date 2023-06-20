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
use Filament\Forms\Components\Radio;
use App\Enums\FamilySpiritualityType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
//use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput\Mask;

use Wiebenieuwenhuis\FilamentCharCounter\Textarea;

trait StudentStatementTrait{

    public function getStudentStatement()
    {
        return [
            Textarea::make('why_did_you_apply')
                ->label("Why do you want to attend St. Ignatius College Preparatory?")
                ->helperText("Up to 500 characters only.")
                ->lazy()
                ->required()
                ->rows(5)
                ->maxLength(500)
                ->afterStateUpdated(function($state){
                    $this->autoSave('why_did_you_apply', $state);
                }),
            Textarea::make('greatest_challenge')
                ->label("What do you think will be your greatest challenge at SI and how do you plan to meet that challenge?")
                ->helperText("Up to 500 characters only.")
                ->lazy()
                ->required()
                ->rows(5)
                ->maxLength(500)
                ->afterStateUpdated(function($state){
                    $this->autoSave('greatest_challenge', $state);
                }),
            Textarea::make('religious_activity_participation')
                ->label("What religious activity or activities do you plan on participating in at SI as part of your spiritual growth and why?")
                ->helperText("Up to 500 characters only.")
                ->lazy()
                ->required()
                ->rows(5)
                ->maxLength(500)
                ->afterStateUpdated(function($state){
                    $this->autoSave('religious_activity_participation', $state);
                }),
            Textarea::make('favorite_and_difficult_subjects')
                ->label("What is your favorite subject in school and why? What subject do you find the most difficult and why?")
                ->helperText("Up to 500 characters only.")
                ->lazy()
                ->required()
                ->rows(5)
                ->maxLength(500)
                ->afterStateUpdated(function($state){
                    $this->autoSave('favorite_and_difficult_subjects', $state);
                }),
            
        ];
    }
}