<?php

namespace App\Http\Livewire\Auth;

use Auth;
use Mail;
use App\Models\User;
use App\Models\Child;
use App\Models\Account;
use App\Models\Parents;
use Livewire\Component;
use App\Enums\AccountAction;
use App\Mail\AccountRequested;
use App\Mail\SetupNewPassword;
use App\Models\AccountRequest;
use Illuminate\Support\HtmlString;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Placeholder;
use Phpsa\FilamentPasswordReveal\Password;
use Filament\Forms\Concerns\InteractsWithForms;

class ReRegistrationLoginPage extends Component implements HasForms
{
    use InteractsWithForms;

    public $email;

    public $is_existing;

    public function render()
    {
        return view('livewire.auth.re-registration-login-page')->layout('layouts.guest');
    }

    public function mount()
    {
        $this->form->fill();
    }

    protected function getFormSchema()
    {
        return [
            TextInput::make('email')
                ->label('')
                ->validationAttribute('email')
                ->placeholder('SI email address')
                ->lazy()
                ->autofocus()
                ->required(),
        ];
    }

    public function next()
    {
        $data  = $this->form->getState();

        $this->is_existing = $this->checkIfExistingChildren($data['email']);

        if($this->is_existing){
            $this->dispatchBrowserEvent('redirect-login');
        }else{
            $this->dispatchBrowserEvent('redirect-register');
        }
    }

    public function checkIfExistingChildren($email)
    {
        return Child::where('si_email', $email)->first();
    }
}
