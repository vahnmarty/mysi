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
            Placeholder::make('section_accomod_form')
                ->label('')
                ->content(new HtmlString('* This section is to be completed by the student.')),
            Placeholder::make('english_placement')
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
            Select::make('reserve_math_challenge')
                ->label('Do you want to make a reservation to take the Math Challenge Test on April 22,2023?')
                ->options([
                    1 => 'Yes',
                    0 => 'No'
                ])
                ->required(),
            Placeholder::make('language_selection')
                ->label('')
                ->content(new HtmlString("<div>
                    <h3 class='font-bold text-primary-blue'>Language Selection</h3>
                    <p class='mt-8 text-sm'>
                    <strong class='text-primary-red'>Please indicate your language choice in the text boxes below:</strong> Options are French, Latin, Mandarin and Spanish
                    (NOTE:  French is not for beginners.  You will need to take a Placement Test to take the class.
                    </p>
                </div>")),
            Select::make('language1')
                ->label('First Choice')
                ->reactive()
                ->inlineLabel()
                ->options(LanguageSelection::asSameArray()),
            Select::make('language2')
                ->label('Second Choice')
                ->reactive()
                ->inlineLabel()
                ->options(LanguageSelection::asSameArray()),
            Select::make('language3')
                ->label('Third Choice')
                ->reactive()
                ->inlineLabel()
                ->options(LanguageSelection::asSameArray()),
            Select::make('language4')
                ->label('Fourth Choice')
                ->reactive()
                ->inlineLabel()
                ->options(LanguageSelection::asSameArray()),
            Placeholder::make('advance_section')
                ->label('')
                ->content(new HtmlString('<p class="text-sm">To place in a more advanced section of your language choice than beginning level, you are required to take a Language Placement Test on April 22, 2023.</p>')),
            Select::make('reserve_language_placement_test')
                ->label('Do you want to make a reservation to take the Language Placement Test on April 22,2023')
                ->options([
                    1 => 'Yes',
                    0 => 'No'
                ])
                ->required(),
            Radio::make('language1_skill')
                ->label('Check that apply to your number 1 ranked language choice')
                ->options(LanguageCapability::asSameArray())
            
            
        ];
    }
}