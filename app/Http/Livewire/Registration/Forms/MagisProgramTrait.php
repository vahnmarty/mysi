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

trait MagisProgramTrait{

    public function getMagisProgramForm()
    {
        return [
            Placeholder::make('section_accomod_form')
                ->label('')
                ->content(new HtmlString('* This section is to be completed by the student.')),
            Placeholder::make('accommodation_text')
                ->label('')
                ->content(new HtmlString("<div>
                    <div>

                        The Magis High School Program is an academic, social and cultural support program for highly motivated students that are underrepresented at St. Ignatius and institutions of higher education.  The Magis Program exists to aid students and families, offering support in navigating the college preparatory system through various workshops, college tours, identity formation experiences and community programming throughout the duration of the high school experience.

                    </div>
                    <p class='mt-8'>The Magis Program supports St. Ignatius students who identity with <strong>at least one</strong> of the following criteria:</p>
                    <ul class='pl-8 mt-8 list-disc'>
                        <li>Students who are first-generation college-bound (Neither parent holds a bachelor’s degree from a US college or university)</li>
                        <li>Students receiving financial assistance   </li>
                        <li>Students of color historically underrepresented in higher education </li>
                    </ul>
                </div>")),
            Radio::make('magis_program.first_gen')
                ->label("Are you a first-generation college-bound student?")
                ->helperText("(Neither parent holds a bachelor's degree from a US college or university)")
                ->options([
                    1 => 'Yes',
                    0 => 'No',
                    2 => 'Unsure'
                ])
                ->required()
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Radio $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveProgram('first_gen', $state);
                }),
            Radio::make('magis_program.is_interested')
                ->label("Are you interested in joining the Magis Program at this time?")
                ->helperText("(If yes, more information about the program and the Magis First-Year Student Retreat will be emailed to you.)")
                ->options([
                    1 => 'Yes',
                    0 => 'No',
                    2 => 'Unsure'
                ])
                ->required()
                ->reactive()
                ->disabled(function(Closure $get){
                    $types = ['A', 'B', 'B1'];
                    $fa = $get('application_status.financial_aid');

                    return in_array($fa, $types);
                })
                ->afterStateHydrated(function(Livewire $livewire, Radio $component, Closure $get, Closure $set, $state){
                    $types = ['A', 'B', 'B1'];
                    $fa = $get('application_status.financial_aid');

                    if(in_array($fa, $types)){
                        $set('magis_program.is_interested', 1);
                    }
                })
                ->afterStateUpdated(function(Livewire $livewire, Radio $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveProgram('is_interested', $state);
                }),
            
        ];
    }

    private function autoSaveProgram($column, $value)
    {
        $this->autoSave($column, $value, 'magis_program');
    }
}