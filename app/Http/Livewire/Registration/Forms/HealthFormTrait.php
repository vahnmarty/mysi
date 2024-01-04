<?php

namespace App\Http\Livewire\Registration\Forms;

use Closure;

use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\School;
use App\Enums\ParentType;
use App\Enums\RacialType;
use App\Enums\CommonOption;
use App\Enums\ReligionType;
use App\Rules\MaxWordCount;
use App\Rules\PhoneNumberRule;
use Illuminate\Support\HtmlString;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Radio;
//use Filament\Forms\Components\Textarea;
use App\Enums\FamilySpiritualityType;
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

trait HealthFormTrait{

    public function getHealthForm()
    {
        return [
            Placeholder::make('section_health_form')
                ->label('')
                ->content(new HtmlString('* This section is to be completed by a parent/guardian.')),
            TextInput::make('medical_insurance_company')
                ->label('Medical Insurance Company')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    //$this->auto('first_name', $state);
                }),
            TextInput::make('medical_policy_number')
                ->label('Medical Policy Number')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    //$this->auto('first_name', $state);
                }),
            TextInput::make('physician_name')
                ->label("Physician's Name")
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    //$this->auto('first_name', $state);
                }),
            TextInput::make('physician_phone')
                ->label("Physician's Phone")
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000'))
                ->rules([new PhoneNumberRule, 'doesnt_start_with:1'])
                ->validationAttribute('Phone Number')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    //$this->autoSaveStudent('mobile_phone', $state);
                }),
            WordTextArea::make('prescribed_medications')
                ->label("Prescribed Medications, Time and Dosages (If not applicable, type 'N/A') ")
                ->helperText("Please limit your answer to 75 words.")
                ->lazy()
                ->required()
                ->rows(5)
                ->wordLimit(75)
                ->rules([
                    new MaxWordCount(75, 100)
                ])
                ->afterStateUpdated(function(Livewire $livewire, WordTextArea $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    //$this->autoSave('why_did_you_apply', $state);
                }),
            WordTextArea::make('allergies')
                ->label("Allergies (Drug, Food, etc.) and other Dietary Restrictions (If not applicable, type 'N/A') ")
                ->helperText("Please limit your answer to 75 words.")
                ->lazy()
                ->required()
                ->rows(5)
                ->wordLimit(75)
                ->rules([
                    new MaxWordCount(75, 100)
                ])
                ->afterStateUpdated(function(Livewire $livewire, WordTextArea $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    //$this->autoSave('why_did_you_apply', $state);
                }),
            WordTextArea::make('other_issues')
                ->label("Please list anything that you would like to share with us regarding your child's physical or mental health that we should know about in order to best support your child. (If not applicable, type 'N/A') ")
                ->helperText("Please limit your answer to 75 words.")
                ->lazy()
                ->required()
                ->rows(5)
                ->wordLimit(75)
                ->rules([
                    new MaxWordCount(75, 100)
                ])
                ->afterStateUpdated(function(Livewire $livewire, WordTextArea $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    //$this->autoSave('why_did_you_apply', $state);
                }),
            
            
        ];
    }
}