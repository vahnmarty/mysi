<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Livewire\Component as Livewire;
use Livewire\TemporaryUploadedFile;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Wiebenieuwenhuis\FilamentCharCounter\Textarea;

trait PlacementFormTrait{

    public function getPlacementForm()
    {
        return [
            Radio::make('has_learning_disability')
                ->label('Would you like to upload any learning difference documentation?')
                ->options([
                    0 => 'No',
                    1 => 'Yes'
                ])
                ->reactive()
                ->afterStateUpdated(function($state){
                    $this->autoSave('has_learning_disability', $state);
                }),
            FileUpload::make('file_learning_documentation')
                ->label('Upload your file here. You can attach multiple files.')
                ->multiple()
                ->maxSize(25000)
                ->reactive()
                ->enableOpen()
                ->enableDownload()
                ->directory("learning_docs/" . $this->app->id)
                ->visible(fn(Closure $get)  =>  $get('has_learning_disability') == 1  )
                ->preserveFilenames()
                ->afterStateUpdated(function(Livewire $livewire, FileUpload $component, $state){
                    // $this->autoSave('file_learning_documentation', $state);
                }),
            Grid::make(1)
                ->schema([
                    Radio::make('entrance_exam_reservation')
                        ->label("Indicate the date and the high school where your child will take the entrance exam. If you submit your application after the November 15th (by midnight) deadline, we may not be able to accommodate you for the HSPT at SI on December 2nd.")
                        ->options([ 
                            "si" => "At SI on " . date('F d, Y', strtotime(settings('placement_test_date'))),
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
                ])
            
        ];
    }
}