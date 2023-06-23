<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Wiebenieuwenhuis\FilamentCharCounter\Textarea;

trait PlacementFormTrait{

    public function getPlacementForm()
    {
        return [
            Radio::make('entrance_exam_reservation')
                ->label("Indicate the date and the high school where your child will take the entrance exam")
                ->options([
                    "si" => "At SI on " . settings('placement_test_date'),
                    "other" => "At Other Catholic High School"
                ])
                ->required()
                ->reactive()
                ->afterStateUpdated(function($state){
                    $this->autoSave('entrance_exam_reservation', $state);
                }),
            Grid::make(1)
                ->visible(fn(Closure $get) => $get('entrance_exam_reservation') === 'other')
                ->schema([
                    TextInput::make('other_catholic_school_name')
                        ->label("Other Catholic School Name")
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function($state){
                            $this->autoSave('other_catholic_school_name', $state);
                        }),
                    TextInput::make('other_catholic_school_location')
                        ->label("Other Catholic School Location")
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function($state){
                            $this->autoSave('other_catholic_school_location', $state);
                        }),
                    DatePicker::make('other_catholic_school_date')
                        ->label("Other Catholic School Date")
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(function($state){
                            $this->autoSave('other_catholic_school_date', $state);
                        }),
                ])
        ];
    }
}