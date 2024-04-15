<?php

namespace App\Http\Livewire\Registration;

use Closure;
use App\Models\Child;
use Livewire\Component;
use App\Models\Registration;
use App\Models\ReRegistration;
use App\Models\ContactDirectory;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Livewire\TemporaryUploadedFile;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;

class ReRegistrationCompleted extends Component implements HasForms
{
    use InteractsWithForms;
    
    public ReRegistration $registration;

    public $directory = [];

    public $data = [];

    public function render()
    {
        return view('livewire.registration.re-registration-completed');
    }

    public function mount($uuid)
    {
        $this->registration = ReRegistration::with('student')->whereUuid($uuid)->firstOrFail();
    }

    
}
