<?php

namespace App\Http\Livewire\Application;

use Arr;
use Closure;
use App\Models\Child;
use App\Enums\GradeLevel;
use App\Models\Application;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Concerns\InteractsWithTable;

class AccommodationDocuments extends Livewire implements HasTable
{
    use InteractsWithTable;

    public $data = [];

    public $model_id, $enable_form = false;
    
    public function render()
    {
        return view('livewire.application.accommodation-documents');
    }

    public function getTableQuery()
    {
        return Application::where('account_id', accountId())->submitted();
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('student_name')
                ->label('Student Name')
                ->formatStateUsing(fn(Application $record) => $record->student->getFullName() ),
            TextColumn::make('mobile_phone')
                ->label('Mobile Phone')
                ->formatStateUsing(fn(Application $record, $state) => format_phone($record->student->mobile_phone)),
            TextColumn::make('personal_email')
                ->label('Email')
                ->formatStateUsing(fn(Application $record, $state) => $record->student->personal_email),
            TextColumn::make('date_submitted')
                ->label('Application Submitted')
                ->formatStateUsing(fn(Application $record, $state) => $record->appStatus->application_submit_date),
        ];
    }

    protected function getTableActions(): array
    {
        return [ 
            Action::make('view')
                ->label('View')
                ->action(function(Application $record){
                    $this->model_id = $record->id;
                    $this->form->fill($record->toArray());
                    $this->enable_form = true;
                    
                })
                ->color(''),
        ];
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getFormSchema()
    {
        return [
            Placeholder::make('si_placement_description')
                ->label('')
                ->content(new HtmlString('Saint Ignatius celebrates neurodiversity and welcomes all kinds of learners. We offer additional support to students through our Center for Academics and Targeted Support (CATS). If your child has a learning difference or other diagnosis and you would like them to receive support from CATS, please upload their diagnostic report (IEP, 504 Plan, Psychological Evaluation, etc.) here.')),
            Radio::make('has_learning_disability')
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
                }),
            FileUpload::make('file_learning_documentation')
                ->label('Upload your file here. You can attach multiple files.')
                ->multiple()
                ->maxSize(25000)
                ->reactive()
                ->required(function(Closure $get){
                    return $get('has_learning_disability');
                })
                ->enableOpen()
                ->enableDownload()
                ->directory("learning_docs/" . date('Ymdhis') . '/' . $this->model_id)
                ->visible(fn(Closure $get)  =>  $get('has_learning_disability') == 1  )
                ->preserveFilenames(),
            Grid::make(1)
                ->schema([
                    Hidden::make('placement_test_date')
                        ->dehydrated(false)
                        ->reactive(),
                    Radio::make('entrance_exam_reservation')
                        ->label("Indicate the date and the high school where your child will take the entrance exam. If you submit your application after the November 15th (by midnight) deadline, we may not be able to accommodate you for the HSPT at SI on December 2nd.")
                        ->options(function(Closure $get){

                            $array =

                            $array = [];
                            $array["At SI on " . date('F j, Y', strtotime( $get('placement_test_date') ))] = "At SI on " . date('F j, Y', strtotime( $get('placement_test_date') ));

                            if($get('has_learning_disability')){
                                $array['At SI on December 9, 2023'] =  "At SI on December 9, 2023 (this date is only for applicants who submit documents for Extended Time)";
                            }
                            
                            $array["At Other Catholic High School"] = "At Other Catholic High School";


                            return $array;
                        })
                        ->required()
                        ->reactive()
                        ->afterStateHydrated(function(Closure $get, Closure $set, $state){
                            if($get('has_learning_disability') &&  $get('file_learning_documentation')) {
                            }else{
                                $set('placement_test_date', settings('placement_test_date'));
                            }
                        })
                        ->afterStateUpdated(function(Livewire $livewire, Radio $component, $state){
                            $livewire->validateOnly($component->getStatePath());
                        }),
                    Grid::make(1)
                        ->visible(fn(Closure $get) => $get('entrance_exam_reservation') === 'At Other Catholic High School')
                        ->schema([
                            TextInput::make('other_catholic_school_name')
                                ->label("Other Catholic School Name")
                                ->required(),
                            TextInput::make('other_catholic_school_location')
                                ->label("Other Catholic School Location")
                                ->required(),
                            DatePicker::make('other_catholic_school_date')
                                ->label("Other Catholic School Date")
                                ->required()
                                ->lazy()
                                ->closeOnDateSelection()
                        ])
                ])
            
        ];
    }
}
