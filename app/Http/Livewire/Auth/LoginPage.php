<?php

namespace App\Http\Livewire\Auth;

use Auth;
use App\Models\User;
use Livewire\Component;
use App\Enums\AccountAction;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class LoginPage extends Component implements HasForms
{
    use InteractsWithForms;

    public $email, $password, $show_password = false;

    public $action;

    public function render()
    {
        return view('livewire.auth.login-page')->layout('layouts.guest');
    }

    public function mount()
    {
        
    }

    protected function getFormSchema()
    {
        return [
            TextInput::make('email')
                ->label('Username/Email')
                ->placeholder('Username or email address')
                ->lazy()
                ->autofocus()
                ->afterStateUpdated(function($state){
                    if($this->checkInternal($state)){
                        $this->showPassword();
                        $this->proceedLogin();
                        $this->show_password = true;
                    }
                })
                ->required(),
            TextInput::make('password')
                ->placeholder('**************')
                ->reactive()
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
        return User::where('username', $email)->orWhere('email', $email)->exists();
    }

    public function fetchCrm($email)
    {
        # Check Salesforce
        return false;
    }

    public function proceedLogin()
    {
        $this->dispatchBrowserEvent('showpassword');
        return $this->action = AccountAction::Login;
    }

    public function showCreateAccount()
    {
        return redirect()->route('account.pending', ['email' =>$this->email]);
        return $this->action = AccountAction::CreateAccount;
    }

    public function noAccount()
    {
        return redirect()->route('register', ['status' => 404]);
    }

    public function showPassword()
    {
        return $this->action == AccountAction::Login;
    }

    public function login()
    {
        $data = $this->form->getState();
        
        if(Auth::attempt([ 'email' => $data['email'] , 'password' => $data['password']]) ){
            return redirect('dashboard');
        }

        if(Auth::attempt([ 'username' => $data['email'] , 'password' => $data['password']]) ){
            return redirect('dashboard');
        }

        $this->addError('login', 'Wrong username or wrong password');

        Notification::make()
            ->title('Unable to login. Check your username and password.')
            ->danger()
            ->send();
    }
}
