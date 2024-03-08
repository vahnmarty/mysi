<?php

namespace App\Http\Livewire\Registration;

use Livewire\Component;
use App\Models\Registration;
use App\Models\ContactDirectory;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;

class RegistrationCompleted extends Component implements HasForms
{
    use InteractsWithForms;
    
    public Registration $registration;

    public $directory = [];

    protected $data = [];

    public function render()
    {
        return view('livewire.registration.registration-completed');
    }

    public function mount($uuid)
    {
        $this->registration = Registration::whereUuid($uuid)->firstOrFail();

        $this->directory = ContactDirectory::orderBy('sort')->get()->toArray();
    }

    protected function getFormSchema()
    {
        return [
            Section::make('Your SI Accounts')
                ->collapsible()
                ->schema([
                    Placeholder::make('your_si_account')
                        ->label('')
                        ->content(new HtmlString('<div>
                            <p class="mt-3">
                                Login today to your new <a  href="https://mail.google.com" target="_blank" class="text-link"><u>SI Google Account</u></a> and access your email using the credentials below. Make sure to use <b>@siprep.org</b>, not @gmail.com.
                            </p>
                        </div>'))
                ])
        ];
    }

    protected function getStatePath()
    {
        return 'data';
    }
    
}
