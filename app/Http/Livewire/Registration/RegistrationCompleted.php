<?php

namespace App\Http\Livewire\Registration;

use Livewire\Component;
use App\Models\Registration;
use App\Models\ContactDirectory;
use Illuminate\Support\HtmlString;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;

class RegistrationCompleted extends Component implements HasForms
{
    use InteractsWithForms;
    
    public Registration $registration;

    public $directory = [];

    public function render()
    {
        return view('livewire.registration.registration-completed');
    }

    public function mount($uuid)
    {
        $this->registration = Registration::whereUuid($uuid)->firstOrFail();

        $this->directory = ContactDirectory::get()->toArray();
    }
    
}
