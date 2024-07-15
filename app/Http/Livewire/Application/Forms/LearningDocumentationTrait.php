<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use Carbon\Carbon;
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
use Filament\Forms\Components\Placeholder;
use Wiebenieuwenhuis\FilamentCharCounter\Textarea;

trait LearningDocumentationTrait{

    public function getLearningForm()
    {
        return [
            Placeholder::make('si_placement_description')
                ->label('')
                ->content(new HtmlString('Saint Ignatius College Preparatory celebrates neurodiversity and welcomes all kinds of learners. We offer additional support to students through our Center for Academics and Targeted Support (CATS). If your child has a learning difference or other diagnosis and you would like them to receive support from CATS and/or receive accommodations on the High School Placement Test, please upload their diagnostic report (IEP, 504 Plan, Psychological Evaluation, etc.) here.')),

            Radio::make('has_learning_difference')
                ->label('Would you like to upload any documents?')
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
                    $this->autoSave('has_learning_difference', $state);
                }),
            FileUpload::make('file_learning_documentation')
                ->label('Upload your file here. You can attach multiple files. Please make sure to select the updated High School Placement test date of December 9th, 2023 above, if you would like to take the test at SI with accommodations.')
                ->multiple()
                ->maxSize(25000)
                ->reactive()
                ->required(function(Closure $get){
                    return $get('has_learning_difference');
                })
                ->enableOpen()
                ->enableDownload()
                ->directory("learning_docs")
                ->visible(fn(Closure $get)  =>  $get('has_learning_difference') == 1  )
                //->preserveFilenames()
                ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                    
                    # Filename: e.g. '137_20207859_original_filename.pdf'
                    return (string) $this->app->id . '_' . date('Ymdhis') . '_' . clean_string($file->getClientOriginalName());
                })
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
            
            
        ];
    }
}