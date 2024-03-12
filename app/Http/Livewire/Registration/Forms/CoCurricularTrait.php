<?php

namespace App\Http\Livewire\Registration\Forms;

use Closure;
use App\Models\Club;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\Sport;
use App\Models\School;
use App\Enums\ShirtSize;
use App\Enums\RacialType;
use App\Enums\AffinityType;
use App\Enums\ReligionType;
use App\Enums\ArtProgramsType;
use App\Rules\PhoneNumberRule;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput\Mask;

trait CoCurricularTrait{

    public function getCurricularForm()
    {
        return [
            Placeholder::make('co_curricular_description')
                ->label('')
                ->content(new HtmlString('
                <p>* This section is to be completed by the student.</p>
                <p class="mt-8">SI offers a variety of clubs, activities, and teams that you can join based on your interests.  For more information about our clubs, click <a href="https://families.siprep.org/students/clubs" target="_blank" class="text-link">here</a>.</p>
            ')),
            Select::make('student.is_interested_club')
                ->label("Would you be interested in joining any of our clubs?")
                ->options([
                    1 => 'Yes',
                    0 => 'No'
                ])
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('is_interested_club', $state);
                }),
            Select::make('student.clubs')
                ->multiple()
                ->maxItems(3)
                ->options(Club::getNameArray())
                ->preload()
                ->required()
                ->visible(fn(Closure $get) => $get('student.is_interested_club'))
                ->label('Choose the top 3 clubs that interest you:')
                ->lazy()
                ->afterStateHydrated(function (Select $component, $state) {
                    if(is_string($state)){
                        $component->state(explode(',', $state));
                    }else{
                        $data = is_array($state) ? $state : [];
                        $component->state($data);
                    }
                })
                ->afterStateUpdated(function(Closure $get, $state){
                    $input = is_array($state) ? implode(',', $state) : $state;
                    $this->autoSaveStudent('clubs', $input);
                }),
            Select::make('student.performing_arts_flag')
                ->options([
                    1 => 'Yes',
                    0 => 'No'
                ])
                ->label('Would you be interested in joining any of our performing arts programs?')
                ->required()
                ->lazy()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('performing_arts_flag', $state);
                }),
            Select::make('student.performing_arts_programs')
                ->multiple()
                ->preload()
                ->options(ArtProgramsType::asSameArray() + ['Other' => 'Other'])
                ->required()
                ->label('Please select all the programs you are interested in')
                ->lazy()
                ->visible(fn (Closure $get) => $get('student.performing_arts_flag') == true)
                ->afterStateHydrated(function (Select $component, $state) {
                    if(is_string($state)){
                        $component->state(explode(',', $state));
                    }else{
                        $data = is_array($state) ? $state : [];
                        $component->state($data);
                    }
                })
                ->afterStateUpdated(function(Closure $get, $state){
                    $input = is_array($state) ? implode(',', $state) : $state;
                    $this->autoSaveStudent('performing_arts_programs', $input);
                }),
            TextInput::make('student.performing_arts_other')
                ->label('If Other, Please specify:')
                ->lazy()
                ->required()
                ->visible(fn (Closure $get) => in_array('Other', $get('student.performing_arts_programs')) )
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('performing_arts_other', $state);
                }),
            TextInput::make('student.instruments')
                ->label('What instrument(s) do you play?')
                ->lazy()
                ->maxLength(100)
                ->visible(function(Closure $get){
                    $array1 = $get('student.performing_arts_programs');
                    $array2 = ['Band and Orchestra', 'Jazz Band', 'PEP Band'];
                    return array_intersect($array1, $array2);
                })
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('instruments', $state);
                }),
            Select::make('student.is_interested_sports')
                ->label("Would you be interested in joining any of our sports teams?")
                ->options([
                    1 => 'Yes',
                    0 => 'No'
                ])
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('is_interested_sports', $state);
                }),
            Select::make('student.sports')
                ->multiple()
                ->maxItems(3)
                ->options(Sport::getNameArray())
                ->required()
                ->label('Choose the top 3 sports you would like to participate in at SI:')
                ->visible(fn(Closure $get) => $get('student.is_interested_sports'))
                ->lazy()
                ->preload()
                ->afterStateHydrated(function (Select $component, $state) {
                    if(is_string($state)){
                        $component->state(explode(',', $state));
                    }else{
                        $data = is_array($state) ? $state : [];
                        $component->state($data);
                    }
                })
                ->afterStateUpdated(function(Closure $get, $state){
                    $input = is_array($state) ? implode(',', $state) : $state;
                    $this->autoSaveStudent('sports', $input);
                }),
            Placeholder::make('student.order_of_interest')
                ->label('')
                ->content(new HtmlString("<div>
                    Please indicate your order of interest regarding the 3 groups above.
                </div>")),
            Select::make('student.interest1')
                ->label('First Choice')
                ->inlineLabel()
                ->options(fn() => $this->getInterestArray(1))
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Select $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveStudent('interest1', $state);
                }),
            Select::make('student.interest2')
                ->label('Second Choice')
                ->inlineLabel()
                ->options(fn() => $this->getInterestArray(2))
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Select $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveStudent('interest2', $state);
                }),
            Select::make('student.interest3')
                ->label('Third Choice')
                ->inlineLabel()
                ->options(fn() => $this->getInterestArray(3))
                ->reactive()
                ->afterStateUpdated(function(Livewire $livewire, Select $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSaveStudent('interest3', $state);
                }),
            Select::make('student.t_shirt_size')
                ->options(ShirtSize::asSameArray())
                ->label('T-Shirt Size (Adult/Unisex)')
                ->lazy()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('t_shirt_size', $state);
                }),
            
            Placeholder::make('affinity_text')
                ->label('')
                ->content(new HtmlString('<div>
                    <h3 class="text-lg font-bold text-primary-blue">SI Affinity Groups</h3>
                    <p class="mt-4">
                        Affinity groups at St. Ignatius are inclusive gatherings where individuals with shared social identities come together voluntarily.  These groups provide a safe space for students who share a common identity, often marginalized, to discuss issues related to that identity, forge connections, and access resources and support from peers and faculty/staff moderators.  Embracing diverse dimensions such as cultural or spiritual identities, affinity groups are instrumental in cultivating awareness and appreciation for diversity within the St. Ignatius community.  They actively contribute to the positive exploration and development of students\' identities, empowering members to contribute to a more inclusive school community.
                    </p>
                    <p class="mt-4">
                        If you wish to join any affinity group(s) at this time, kindly select from the current available affinity groups at SI.  Affinity groups are intended for individuals who identify as members of the group and can share their experiences from a first-person perspective ("I").
                    </p>
                </div>')),
            Select::make('student.affinity')
                ->multiple()
                ->options(AffinityType::asSameArray())
                ->required()
                ->label('')
                ->lazy()
                ->preload()
                ->searchable()
                ->afterStateHydrated(function (Select $component, $state) {
                    if(is_string($state)){
                        $component->state(explode(',', $state));
                    }else{
                        $data = is_array($state) ? $state : [];
                        $component->state($data);
                    }
                })
                ->afterStateUpdated(function(Closure $get, $state){
                    $input = is_array($state) ? implode(',', $state) : $state;
                    $this->autoSaveStudent('affinity', $input);
                }),
            
        ];
    }

    private function getInterestArray($exceptNumber)
    {
        $orgs = ['Clubs', 'Performing Arts Programs', 'Sports'];
        $orgs = array_combine($orgs, $orgs);
        $data = $this->data['student'];
        $selected = [];
    

        foreach(range(1,3) as $i => $choice)
        {
            if($choice != $exceptNumber)
            {
                if( !empty($data['interest' . $choice]) ){
                    unset($orgs[$data['interest' . $choice]]);
                }
            }
            
        }
        
        return $orgs;
    }

}