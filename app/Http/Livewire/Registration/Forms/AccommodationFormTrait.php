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

trait AccommodationFormTrait{

    public function getAccommodationForm()
    {
        return [
            Placeholder::make('accommodation.section_accomod_form')
                ->label('')
                ->content(new HtmlString('* This section is to be completed by a parent/guardian.')),
            Placeholder::make('accommodation.accommodation_text')
                ->label('')
                ->content(new HtmlString("<div class='text-sm'>
                St. Ignatius welcomes all types of learners.  Our <strong>CATS (Center for Academics and Targeted Support)</strong> program provides academic support and accommodations to students that have learning differences or other diagnosis(es) that may impact learning.  If your child would like to access services from CATS, please email documentation of their needs to <a href='mailto:ggalletta@siprep.org' class='text-link'>ggalletta@siprep.org</a>.  Documentation can be (but is not limited to):  an IEP, 504 Plan, psychological educational evaluation or doctor's letter.  Documents must contain a specific diagnosis.  If you have further questions, please contact our CATS Director, Gianna Galletta, at <a href='mailto:ggalletta@siprep.org' class='text-link'>ggalletta@siprep.org</a>.  We look forward to working with you!
                </div>")),
            Radio::make('accommodation.formal')
                ->label('Does the student receive formal academic accommodations at their current school (Learning Plan, IEP, 504 Plan, Other)?')
                ->options([
                    1 => 'Yes',
                    0 => 'No'
                ])
                ->required()
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Radio $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveAccommodation('formal', $state);
                }),
            Radio::make('accommodation.informal')
                ->label('Does the student receive informal academic accommodations at their current school (e.g., extended time, preferred seating)? 
                ')
                ->options([
                    1 => 'Yes',
                    0 => 'No'
                ])
                ->required()
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Radio $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveAccommodation('informal', $state);
                }),
            
        ];
    }

    private function autoSaveAccommodation($column, $value)
    {
        $this->autoSave($column, $value, 'accommodation');
    }
}