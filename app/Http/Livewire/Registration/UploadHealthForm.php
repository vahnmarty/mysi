<?php

namespace App\Http\Livewire\Registration;

use Closure;
use App\Models\Child;
use Livewire\Component;
use App\Models\Registration;
use Illuminate\Support\HtmlString;
use Livewire\TemporaryUploadedFile;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;

class UploadHealthForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $health_form_file, $completed;

    public Registration $registration;

    public Child $student;

    public function render()
    {
        $this->completed = !empty($this->health_form_file);
        
        return view('livewire.registration.upload-health-form');
    }

    public function mount($registrationUuid)
    {
        $this->registration = Registration::whereUuid($registrationUuid)->firstOrFail();

        $student = $this->registration->student;

        $this->student = $student;

        $this->form->fill([
            'health_form_file' => $student->health_form_file
        ]);

        
    }

    protected function getFormSchema()
    {
        return [
            FileUpload::make('health_form_file')
                ->label(new HtmlString('<p>Upload your SFUSD Freshman Health Form here. Please use the following naming convention for the file:</p>
                <strong class="text-danger">{Student_First_Name}_{Student_Last_Name}_{Name_of_File}.pdf</strong></p>'))
                ->helperText(new HtmlString("(The file doesn’t have to be a PDF.)<p class='mt-4'><strong>NOTE:</strong> Do NOT upload the Ticket to Play Medical Clearance Form here.</p>"))
                ->maxSize(25000)
                ->reactive()
                ->enableOpen()
                ->enableDownload()
                ->directory("health_form_files")
                ->multiple()
                //->preserveFilenames()
                ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                    return (string) $this->student->id . '_' . $this->student->first_name .'_' .$this->student->last_name  .'/' . clean_string($file->getClientOriginalName());
                })
                ->afterStateHydrated(function(Closure $get, Closure $set, $state){
                })
                ->afterStateUpdated(function(Component $livewire, FileUpload $component, Closure $get, Closure $set, $state){
                    $component->saveUploadedFiles();
                    $files = \Arr::flatten($component->getState());
                    $this->saveFile($files);
                })
                ->required(),
        ];
    }

    public function saveFile($files)
    {
        $student = $this->student;
        $student->health_form_file = $files;
        $student->save();
    }

}
