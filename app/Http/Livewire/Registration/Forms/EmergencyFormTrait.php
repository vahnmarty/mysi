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
            Placeholder::make('emergency_contact.section_health_form')
                ->label('')
                ->content(new HtmlString('* This section is to be completed by a parent/guardian.')),
            TextInput::make('emergency_contact.full_name')
                ->label('Emergency Contact Name (if parents/guardians are unavailable):')
                ->lazy()
                ->required()
                ->afterStateUpdated(function(Livewire $livewire, TextInput $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveEmergency('full_name', $state);
                }),
            Select::make('emergency_contact.relationship')
                ->label('Emergency Contact Relationship')
                ->lazy()
                ->required()
                ->options(EmergencyContactRelationshipType::asSameArray())
                ->afterStateUpdated(function(Livewire $livewire, Select $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveEmergency('relationship', $state);
                }),
            TextInput::make('emergency_contact.home_phone')
                ->label("Emergency Contact Home Phone")
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000'))
                ->rules([new PhoneNumberRule, 'doesnt_start_with:1'])
                ->validationAttribute('Phone Number')
                ->lazy()
                ->required()
                ->afterStateHydrated(function(Closure $set, $state){
                    if(!$state){
                        $set('emergency_contact.home_phone', '');
                    }
                })
                ->afterStateUpdated(function(Livewire $livewire, TextInput $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveEmergency('home_phone', $state);
                }),
            TextInput::make('emergency_contact.mobile_phone')
                ->label("Emergency Contact Mobile Phone")
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000'))
                ->rules([new PhoneNumberRule, 'doesnt_start_with:1'])
                ->validationAttribute('Phone Number')
                ->lazy()
                ->required()
                ->afterStateHydrated(function(Closure $set, $state){
                    if(!$state){
                        $set('emergency_contact.mobile_phone', '');
                    }
                })
                ->afterStateUpdated(function(Livewire $livewire, TextInput $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveEmergency('mobile_phone', $state);
                }),
            TextInput::make('emergency_contact.work_phone')
                ->label("Emergency Contact Work Phone")
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000'))
                ->rules([new PhoneNumberRule, 'doesnt_start_with:1'])
                ->validationAttribute('Phone Number')
                ->lazy()
                ->required()
                ->afterStateHydrated(function(Closure $set, $state){
                    if(!$state){
                        $set('emergency_contact.work_phone', '');
                    }
                })
                ->afterStateUpdated(function(Livewire $livewire, TextInput $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveEmergency('work_phone', $state);
                }),
            TextInput::make('emergency_contact.work_phone_ext')
                ->label('Emergency Contact Work Phone Extension')
                ->lazy()
                ->afterStateUpdated(function(Livewire $livewire, TextInput $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveEmergency('work_phone_ext', $state);
                }),
            
            
        ];
    }

    private function autoSaveEmergency($column, $value)
    {
        $this->autoSave($column, $value, 'emergency_contact');
    }
}