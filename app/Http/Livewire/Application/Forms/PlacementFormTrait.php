<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Livewire\Component as Livewire;
use Livewire\TemporaryUploadedFile;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Wiebenieuwenhuis\FilamentCharCounter\Textarea;
use Carbon\Carbon;

trait PlacementFormTrait{

    public function getPlacementForm()
    {
        return [
            Radio::make('has_learning_disability')
                ->label('Would you like to upload any learning difference documentation?')
                ->options([
                    1 => 'Yes',
                    0 => 'No',
                ])
                ->reactive()
                ->required()
                ->afterStateUpdated(function(Closure $get, Closure $set, $state){
                    if($state){
                        // if($get('file_learning_documentation')){
                        //     if(count($get('file_learning_documentation'))){
                        //         $date = Carbon::parse(settings('placement_test_date'))->addDays(7)->format('Y-m-d');
                        //         $set('placement_test_date', $date);
                        //     }
                        // }
                    }
                    else{
                        $set('placement_test_date', settings('placement_test_date'));
                    }
                    $this->autoSave('has_learning_disability', $state);
                }),
            FileUpload::make('file_learning_documentation')
                ->label('Upload your file here. You can attach multiple files.')
                ->multiple()
                ->maxSize(25000)
                ->reactive()
                ->enableOpen()
                ->enableDownload()
                ->directory("learning_docs/" . date('Ymdhis') . '/' . $this->app->id)
                ->visible(fn(Closure $get)  =>  $get('has_learning_disability') == 1  )
                ->preserveFilenames()
                ->afterStateHydrated(function(Closure $get, Closure $set, $state){
                    // if($state){
                    //     $date = Carbon::parse(settings('placement_test_date'))->addDays(7)->format('Y-m-d');
                    //     $set('placement_test_date', $date);
                    // }
                })
                ->afterStateUpdated(function(Livewire $livewire, FileUpload $component, Closure $get, Closure $set, $state){
                    $component->saveUploadedFiles();
                    $files = Arr::flatten($component->getState());
                    $this->autoSaveFiles('file_learning_documentation', $files);
                    // if(count($files)){
                    //     $date = Carbon::parse(settings('placement_test_date'))->addDays(7)->format('Y-m-d');
                    //     $set('placement_test_date', $date);
                    // }
                }),
            Grid::make(1)
                ->schema([
                    Hidden::make('placement_test_date')
                        ->dehydrated(false)
                        ->reactive(),
                    Radio::make('entrance_exam_reservation')
                        ->label("Indicate the date and the high school where your child will take the entrance exam. If you submit your application after the November 15th (by midnight) deadline, we may not be able to accommodate you for the HSPT at SI on December 2nd.")
                        ->options(function(Closure $get){

                            $array = [];
                            $array[] = "At SI on " . date('F j, Y', strtotime( $get('placement_test_date') ));

                            if($get('has_learning_disability')){
                                $array[] =  "At SI on December 9, 2023 (this date is only for those who qualify for Extended Time)";
                            }
                            
                            $array[] = "At Other Catholic High School";


                            return array_combine($array, $array);
                        })
                        ->required()
                        ->reactive()
                        ->afterStateHydrated(function(Closure $get, Closure $set, $state){
                            if($get('has_learning_disability') &&  count($get('file_learning_documentation'))){
                                //$date = Carbon::parse(settings('placement_test_date'))->addDays(7)->format('Y-m-d');
                                //$set('placement_test_date', $date);
                            }else{
                                $set('placement_test_date', settings('placement_test_date'));
                            }
                        })
                        ->afterStateUpdated(function($state){
                            $this->autoSave('entrance_exam_reservation', $state);
                        }),
                    Grid::make(1)
                        ->visible(fn(Closure $get) => $get('entrance_exam_reservation') === 'At Other Catholic High School')
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
                                ->closeOnDateSelection()
                                ->afterStateUpdated(function($state){
                                    $this->autoSave('other_catholic_school_date', $state);
                                }),
                        ])
                ])
            
        ];
    }
}