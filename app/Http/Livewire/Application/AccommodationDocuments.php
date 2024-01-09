<?php

namespace App\Http\Livewire\Application;

use Arr;
use Mail;
use Closure;
use App\Models\Child;
use App\Enums\GradeLevel;
use App\Models\Application;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Livewire\Component as Livewire;
use Livewire\TemporaryUploadedFile;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use App\Mail\SubmittedApplicationDocuments;
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
        return Child::where('account_id', accountId())->has('submittedApplication');
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('student_name')->label('Student Name')->formatStateUsing(fn(Child $record) => $record->getFullName() ),
            TextColumn::make('mobile_phone')
                ->label('Mobile Phone')
                ->formatStateUsing(fn($state) => format_phone($state)),
            TextColumn::make('personal_email')->label('Personal Email'),
            TextColumn::make('current_school')
                ->label('Current School')
                ->wrap()
                ->formatStateUsing(fn(Child $record) => $record->getCurrentSchool()),
            TextColumn::make('current_grade')->label('Current Grade'),
        ];
    }

    protected function getTableActions(): array
    {
        return [ 
            Action::make('documents')
                ->label(fn(Child $record) => $record->documents()->count() ? 'Documents Sent' : 'Send Documents')
                ->disabled(fn(Child $record) => $record->documents()->count())
                ->action(function(Child $record){
                    $this->model_id = $record->id;
                    $this->form->fill();
                    $this->enable_form = true;
                    
                })
                ->color(''),
        ];
    }

    protected function isTablePaginationEnabled(): bool 
    {
        return false;
    }

    protected function getTableActionsColumnLabel(): ?string
    {
        return 'Action';
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No records found';
    }
 
    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Accommodation documents cannot be sent to SI if there were no applications submitted.';
    }

    protected function getFormSchema()
    {
        return [
            Placeholder::make('si_placement_description')
                ->label('')
                ->content(new HtmlString('Saint Ignatius celebrates neurodiversity and welcomes all kinds of learners. We offer additional support to students through our Center for Academics and Targeted Support (CATS). If your child has a learning difference or other diagnosis and you would like them to receive support from CATS, please upload their diagnostic report (IEP, 504 Plan, Psychological Evaluation, etc.) here.')),
            Radio::make('has_learning_difference')
                ->label('Would you like to upload any documents?')
                ->default(1)
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
                    return $get('has_learning_difference');
                })
                ->enableOpen()
                ->enableDownload()
                ->directory("accommodation_docs")
                ->visible(fn(Closure $get)  =>  $get('has_learning_difference') == 1  )
                // ->preserveFilenames(),
                ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                    return (string) $this->model_id . '_' . date('Ymdhis') . '_' . clean_string($file->getClientOriginalName());
                })
            
        ];
    }

    public function save()
    {
        $data = $this->form->getState();

        if($data['has_learning_difference']){

            if(count($data['file_learning_documentation']))
            {
                $child = Child::find($this->model_id);
                $document = $child->documents()->create(['file' => $data['file_learning_documentation']]);

                Mail::to(['ggalletta@siprep.org','pcollins@siprep.org'])
                    ->bcc('admissions@siprep.org')
                    ->bcc('rferro@siprep.org')
                    ->send(new SubmittedApplicationDocuments($document));

                Notification::make()
                    ->title('Accommodation Documents sent!')
                    ->success()
                    ->send();

                $this->reset('data');
            }
        }

        $this->enable_form = false;
    }

    public function cancel()
    {
        $this->form->fill();
        $this->enable_form = false;
    }
}
