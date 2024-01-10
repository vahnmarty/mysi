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
use App\Enums\LanguageSelection;
use App\Enums\LanguageCapability;
use Illuminate\Support\HtmlString;
//use Filament\Forms\Components\Textarea;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Radio;
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

trait CoursePlacementTrait{

    public function getCoursePlacementForm()
    {
        return [
            Placeholder::make('course_placement.section_accomod_form')
                ->label('')
                ->content(new HtmlString('* This section is to be completed by the student.')),
            Placeholder::make('course_placement.english_placement')
                ->label('')
                ->content(new HtmlString("<div class='space-y-8'>
                    <div class='space-y-4'>
                        <h3 class='font-bold text-primary-blue'>English Placement</h3>

                        <p>You have been placed in: ______________________</p>

                        <p>NOTE:  Any interested freshman students will have an opportunity to apply for Honors English in the spring semester of their freshman year for the following school year (sophomore year).</p>

                    </div>
                    <div class='space-y-4'>
                        <h3 class='font-bold text-primary-blue'>Math Placement</h3>

                        <p>You have been placed in: ______________________</p>

                        <p>
                            If you want to challenge your math placement, you are required to take the Challenge Test on April 22, 2023.    
                        </p>

                    </div>
                </div>")),
            Select::make('course_placement.math_challenge')
                ->label('Do you want to make a reservation to take the Math Challenge Test on April 22,2023?')
                ->options([
                    1 => 'Yes',
                    0 => 'No'
                ])
                ->required()
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Select $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveCourse('math_challenge', $state);
                }),
            Placeholder::make('course_placement.language_selection')
                ->label('')
                ->content(new HtmlString("<div>
                    <h3 class='font-bold text-primary-blue'>Language Selection</h3>
                    <p class='mt-8 text-sm'>
                    <strong class='text-primary-red'>Please indicate your language choice in the text boxes below:</strong> Options are French, Latin, Mandarin and Spanish
                    (NOTE:  French is not for beginners.  You will need to take a Placement Test to take the class.
                    </p>
                </div>")),
            Select::make('course_placement.language1')
                ->label('First Choice')
                ->inlineLabel()
                ->options(LanguageSelection::asSameArray())
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Select $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveCourse('language1', $state);
                }),
            Select::make('course_placement.language2')
                ->label('Second Choice')
                ->inlineLabel()
                ->options(LanguageSelection::asSameArray())
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Select $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveCourse('language2', $state);
                }),
            Select::make('course_placement.language3')
                ->label('Third Choice')
                ->inlineLabel()
                ->options(LanguageSelection::asSameArray())
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Select $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveCourse('language3', $state);
                }),
            Select::make('course_placement.language4')
                ->label('Fourth Choice')
                ->inlineLabel()
                ->options(LanguageSelection::asSameArray())
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Select $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveCourse('language4', $state);
                }),
            Placeholder::make('course_placement.advance_section')
                ->label('')
                ->content(new HtmlString('<p class="text-sm">To place in a more advanced section of your language choice than beginning level, you are required to take a Language Placement Test on April 22, 2023.</p>')),
            Select::make('course_placement.reserve_language_placement')
                ->label('Do you want to make a reservation to take the Language Placement Test on April 22,2023')
                ->options([
                    1 => 'Yes',
                    0 => 'No'
                ])
                ->required()
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Select $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveCourse('reserve_language_placement', $state);
                }),
            Radio::make('course_placement.language1_skill')
                ->label('Check that apply to your number 1 ranked language choice')
                ->options(LanguageCapability::asSameArray())
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Radio $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveCourse('language1_skill', $state);
                }),
            
            
        ];
    }

    private function autoSaveCourse($column, $value)
    {
        $this->autoSave($column, $value, 'course_placement');
    }
}