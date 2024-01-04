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
use App\Enums\EmergencyContactRelationshipType;
use Wiebenieuwenhuis\FilamentCharCounter\Textarea;

trait EmergencyFormTrait{

    public function getEmergencyForm()
    {
        return [
            Placeholder::make('section_health_form')
                ->label('')
                ->content(new HtmlString('* This section is to be completed by a parent/guardian.')),
            TextInput::make('name')
                ->label('Emergency Contact Name (if parents/guardians are unavailable):')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    //$this->auto('first_name', $state);
                }),
            Select::make('relationship')
                ->label('Emergency Contact Relationship')
                ->lazy()
                ->required()
                ->options(EmergencyContactRelationshipType::asSameArray())
                ->afterStateUpdated(function($state){
                    //$this->auto('first_name', $state);
                }),
            TextInput::make('home_phone')
                ->label("Emergency Contact Home Phone")
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000'))
                ->rules([new PhoneNumberRule, 'doesnt_start_with:1'])
                ->validationAttribute('Phone Number')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    //$this->autoSaveStudent('mobile_phone', $state);
                }),
            TextInput::make('mobile_phone')
                ->label("Emergency Contact Mobile Phone")
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000'))
                ->rules([new PhoneNumberRule, 'doesnt_start_with:1'])
                ->validationAttribute('Phone Number')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    //$this->autoSaveStudent('mobile_phone', $state);
                }),
            TextInput::make('work_phone')
                ->label("Emergency Contact Work Phone")
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000'))
                ->rules([new PhoneNumberRule, 'doesnt_start_with:1'])
                ->validationAttribute('Phone Number')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    //$this->autoSaveStudent('mobile_phone', $state);
                }),
            
            
        ];
    }
}