<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use App\Enums\AccountAction;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class LoginPage extends Component implements HasForms
{
    use InteractsWithForms;

    public $email, $password;

    public $action;

    public function render()
    {
        return view('livewire.auth.login-page')->layout('layouts.guest');
    }

    protected function getFormSchema()
    {
        return [
            TextInput::make('email')
                ->label('Username/Email')
                ->placeholder('Enter your email address')
                ->reactive()
                ->email()
                ->autofocus()
                ->required(),
            TextInput::make('password')
                ->placeholder('**************')
                ->reactive()
                ->hidden($this->showPassword())
                ->password()
                ->required(),
        ];
    }

    public function next()
    {
        if( $this->checkInternal($this->email) ){
            return $this->proceedLogin();
        }

        if( $this->fetchCrm($this->email) ){
            return $this->showCreateAccount();
        }

        return $this->noAccount();
    }

    public function checkInternal($email)
    {
        # Check Internal DB
        return false;
    }

    public function fetchCrm($email)
    {
        # Check Salesforce
        return true;
    }

    public function proceedLogin()
    {
        $this->dispatchBrowserEvent('showpassword');
        return $this->action = AccountAction::Login;
    }

    public function showCreateAccount()
    {
        return $this->action = AccountAction::CreateAccount;
    }

    public function noAccount()
    {
        return $this->action = AccountAction::NoAccount;
    }

    public function showPassword()
    {
        return $this->action == AccountAction::Login;
    }
}
