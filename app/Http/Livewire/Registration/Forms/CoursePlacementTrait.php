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
use App\Enums\LanguageFacts;
use App\Rules\PhoneNumberRule;
use App\Enums\LanguageSelection;
use App\Enums\LanguageCapability;
//use Filament\Forms\Components\Textarea;
use Illuminate\Support\HtmlString;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Radio;
use App\Enums\FamilySpiritualityType;
use Filament\Forms\Components\Select;
use App\Forms\Components\WordTextArea;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\CheckboxList;
use App\Forms\Components\CustomCheckboxList;
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

            # English

            Placeholder::make('course_placement.english_placement')
                ->label('')
                ->content(fn(Closure $get) => new HtmlString('<div class="space-y-8">
                    <div class="space-y-4">
                        <h3 class="font-bold text-primary-blue">English Placement</h3>

                        <p>You have been placed in: <u><strong class="text-primary-red">&nbsp;&nbsp;&nbsp;'. $get('application_status.english_class') .'&nbsp;&nbsp;&nbsp;</strong></u></p>
                    </div>
                </div>')),
            Checkbox::make('course_placement.english_placement_opt')
                ->visible(fn(Closure $get) => $get('application_status.english_class'))
                ->label("Opt out and take the next level below.  Students who opt out of any Honors course now will still be eligible for Honors courses in the future based on GPA requirements."),
            Placeholder::make('course_placement.english_placement_note')
                ->label('')
                ->content(fn(Closure $get) => new HtmlString('<p>NOTE: Any interested Frosh students will have an opportunity to apply for Honors English in the spring semester of their Frosh year for the following school year (Sophomore year).</p>')),

            # Math
            Placeholder::make('course_placement.math_placement')
                ->label('')
                ->content(fn(Closure $get) => new HtmlString('<div class="space-y-8">
                    <div class="space-y-4">
                        <h3 class="font-bold text-primary-blue">Math Placement</h3>

                        <p>You have been placed in: <u><strong class="text-primary-red">&nbsp;&nbsp;&nbsp;'. $get('application_status.math_class') .'&nbsp;&nbsp;&nbsp;</strong></u></p>
                    </div>
                </div>')),
            Checkbox::make('course_placement.math_placement_opt')
                ->visible(fn(Closure $get) => $get('application_status.math_class'))
                ->label("Opt out and take the next level below.  Students who opt out of any Honors course now will still be eligible for Honors courses in the future based on GPA requirements."),
            Placeholder::make('course_placement.math_placement_note')
                ->label('')
                ->content(fn(Closure $get) => new HtmlString('
                    <div class="space-y-4">
                        <p>
                            If you want to challenge your math placement, you are required to take the Challenge Test on '. app_variable('challenge_test_date') .'.    
                        </p>
                    </div>
                ')),
            Select::make('course_placement.math_challenge')
                ->label('Do you want to make a reservation to take the Math Challenge Test on ' . app_variable('challenge_test_date') . '?')
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

            # Biology
            
            Placeholder::make('course_placement.biology_placement')
                ->label('')
                ->content(fn(Closure $get) => new HtmlString('<div class="space-y-8">
                    <div class="space-y-4">
                        <h3 class="font-bold text-primary-blue">Biology Placement</h3>

                        <p>You have been placed in: <u><strong class="text-primary-red">&nbsp;&nbsp;&nbsp;'. $get('application_status.bio_class') .'&nbsp;&nbsp;&nbsp;</strong></u></p>
                    </div>
                </div>')),
            Checkbox::make('course_placement.biology_placement_opt')
                ->visible(fn(Closure $get) => $get('application_status.bio_class'))
                ->label("Opt out and take the next level below.  Students who opt out of any Honors course now will still be eligible for Honors courses in the future based on GPA requirements."),
            Placeholder::make('course_placement.biology_placement_note')
                ->label('')
                ->content(fn(Closure $get) => new HtmlString('<div class="space-y-8">
                    <div class="space-y-4">
                        <p>
                            If you want to challenge your biology placement, you are required to take the Challenge Test on '. app_variable('challenge_test_date') . '? 
                        </p>
                    </div>
                </div>')),
            Select::make('course_placement.biology_challenge')
                ->label('Do you want to make a reservation to take the Biology Challenge Test on ' . app_variable('challenge_test_date') . '?')
                ->options([
                    1 => 'Yes',
                    0 => 'No'
                ])
                ->required()
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Select $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveCourse('biology_challenge', $state);
                }),
            Placeholder::make('course_placement.language_selection')
                ->label('')
                ->content(new HtmlString("<div>
                    <h3 class='font-bold text-primary-blue'>Language Selection</h3>
                    <p class='mt-4'>
                    <strong class='text-primary-red'>Please indicate your language choice in the text boxes below:</strong> Options are French, Latin, Mandarin and Spanish
                    (NOTE:  French for beginners is not offered.  You will need to take a Placement Test and place into French 3 or above in order to take the class.
                    </p>
                </div>")),
            Select::make('course_placement.language1')
                ->label('First Choice')
                ->inlineLabel()
                ->options(fn() => $this->getLanguageArray(1))
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Select $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveCourse('language1', $state);
                }),
            Select::make('course_placement.language2')
                ->label('Second Choice')
                ->inlineLabel()
                ->options(fn() => $this->getLanguageArray(2))
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Select $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveCourse('language2', $state);
                }),
            Select::make('course_placement.language3')
                ->label('Third Choice')
                ->inlineLabel()
                ->options(fn() => $this->getLanguageArray(3))
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Select $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveCourse('language3', $state);
                }),
            Select::make('course_placement.language4')
                ->label('Fourth Choice')
                ->inlineLabel()
                ->options(fn() => $this->getLanguageArray(4))
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Select $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveCourse('language4', $state);
                }),
            Placeholder::make('course_placement.advance_section')
                ->label('')
                ->content(new HtmlString('<p>To place above the beginning level of your Language Choice, you must take the Placement Test on ' . app_variable('challenge_test_date')  .'.</p>')),

            Select::make('course_placement.reserve_language_placement')
                ->label('Do you want to make a reservation to take the Language Placement Test on ' . app_variable('challenge_test_date') . '?')
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
            
            Select::make('course_placement.language_challenge_choice')
                ->label('What language?')
                ->options(LanguageSelection::asSameArray())
                ->required()
                ->reactive()
                ->visible(fn (Closure $get) =>  $get('course_placement.reserve_language_placement') )
                ->afterStateUpdated(function(Livewire $livewire, Select $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveCourse('language_challenge_choice', $state);
                }),
            Radio::make('course_placement.language1_skill')
                ->label('Which of the following is true about your number 1 ranked language choice? (Select one) ')
                ->options(LanguageCapability::asSameArray())
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Radio $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveCourse('language1_skill', $state);
                }),
            CustomCheckboxList::make('course_placement.language1_skill_list')
                ->label('Also, which of the following applies to your number 1 ranked language choice? (Check all that applies) ')
                ->options(LanguageFacts::asSameArray())
                ->reactive()
                ->afterStateHydrated(function (CustomCheckboxList $component, $state) {
                    if(is_string($state)){
                        $component->state(explode(',', $state));
                    }else{
                        $data = is_array($state) ? $state : [];
                        $component->state($data);
                    }
                })
                ->afterStateUpdated(function(Livewire $livewire, CustomCheckboxList $component, Closure $get, Closure $set, $state){
                    // if(in_array(LanguageFacts::level0, $state)){

                    //     if($state[0] == LanguageFacts::level0){
                    //         array_shift($state);
                    //         $set('course_placement.language1_skill_list', $state);
                    //     }else{
                    //         $state = [LanguageFacts::level0];
                    //         $set('course_placement.language1_skill_list', $state);
                    //     }
                        
                    // }

                    if(in_array(LanguageFacts::level0, $state)){
                        $state = [LanguageFacts::level0];
                    }
                    $livewire->validateOnly($component->getStatePath());
                    $input = is_array($state) ? implode(',', $state) : $state;
                    $this->autoSaveCourse('language1_skill_list', $input);
                })
                ->disableOptionWhen(LanguageFacts::level0),
            
            
        ];
    }

    private function autoSaveCourse($column, $value)
    {
        $this->autoSave($column, $value, 'course_placement');
    }

    private function getLanguageArray($exceptNumber)
    {
        $languages = LanguageSelection::asSameArray();
        $data = $this->data['course_placement'];
        $selected = [];
    

        foreach(range(1,4) as $i => $lang)
        {
            if($lang != $exceptNumber)
            {
                $selectedLanguage = $data['language' . $lang];
                if( !empty($data['language' . $lang]) ){
                    unset($languages[$data['language' . $lang]]);
                }
            }
            
        }
        
        return $languages;
    }
}