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
                <p>* This section is to be completed by a student.</p>
                <p class="mt-8">SI offers a variety of clubs, activities, and teams that you can join based on your interests.  For more information about our clubs, click <a href="https://families.siprep.org/students/clubs" target="_blank" class="text-link">here</a>.</p>
            ')),
            Select::make('student.clubs')
                ->multiple()
                ->maxItems(3)
                ->options(Club::getNameArray())
                ->preload()
                ->required()
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
            Select::make('student.sports')
                ->multiple()
                ->maxItems(3)
                ->options(Sport::getNameArray())
                ->required()
                ->label('Choose the top 3 sports you would like to participate in at SI:')
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
            Select::make('student.t_shirt_size')
                ->options(ShirtSize::asSameArray())
                ->label('T-Shirt Size (Adult/Unisex)')
                ->lazy()
                ->afterStateUpdated(function($state){
                    $this->autoSaveStudent('t_shirt_size', $state);
                }),
        ];
    }

}