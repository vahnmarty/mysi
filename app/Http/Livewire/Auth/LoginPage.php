<?php

namespace App\Http\Livewire\Auth;

use Auth;
use App\Models\User;
use App\Models\Parents;
use Livewire\Component;
use App\Enums\AccountAction;
use Illuminate\Support\HtmlString;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Placeholder;
use Phpsa\FilamentPasswordReveal\Password;
use Filament\Forms\Concerns\InteractsWithForms;

class LoginPage extends Component implements HasForms
{
    use InteractsWithForms;

    public $email, $password, $show_password = false;
    
    public $has_primary_owner, $primary_parent_name;

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
                ->label('')
                ->validationAttribute('username/email')
                ->placeholder('Username or email address')
                ->lazy()
                ->autofocus()
                // ->afterStateUpdated(function($state){
                //     if($this->checkInternal($state)){
                //         dd($state);
                //         $this->showPassword();
                //         $this->proceedLogin();
                //         $this->show_password = true;
                //     }
                // })
                ->required(),
            Placeholder::make('account_existing')
                ->label('')
                ->reactive()
                ->visible(fn() => $this->has_primary_owner )
                ->content(fn() => new HtmlString('<p class="text-primary-red">* Your email is associated with an existing account. Please follow up with <strong>'.$this->primary_parent_name.'</strong> to access this account.</p>')),
            Password::make('password')
                ->label('')
                ->validationAttribute('Password')
                ->placeholder('Password')
                ->reactive()
                ->password()
                ->required()
                ->revealable()
                ->visible(fn() => $this->show_password),
        ];
    }

    public function next()
    {
        $form  = $this->form->getState();

        if( $this->checkFromUsers($form['email']) ) {
            return $this->proceedLogin();
        }

        if( $this->checkFromParents($form['email']) ){
            $parent = Parents::with('account.users')->where('personal_email', $form['email'])->first();
            $account = $parent->account;

            if($account->parents()->count() > 1)
            {
                $primary_parent = $account->primaryParent;

                $this->has_primary_owner = true;
                $this->primary_parent_name = $primary_parent->getFullName();

                return;
            }
        }

        return $this->noAccount();
    }

    public function checkFromUsers($email)
    {
        return User::where('username', $email)->orWhere('email', $email)->exists();
    }

    public function checkFromParents($email)
    {
        return Parents::where('personal_email', $email)->exists();
    }

    public function fetchCrm($email)
    {
        # Check Salesforce
        return false;
    }

    public function proceedLogin()
    {
        $this->show_password = true;

        return $this->action = AccountAction::Login;
    }

    public function showCreateAccount()
    {
        return redirect()->route('account.pending', ['email' =>$this->email]);
        return $this->action = AccountAction::CreateAccount;
    }

    public function noAccount()
    {
        return redirect()->route('register', ['status' => 404, 'email' => $this->email]);
    }

    public function showPassword()
    {
        return $this->action == AccountAction::Login;
    }

    public function login()
    {
        $data = $this->form->getState();

        if(!isset($data['password'])){
            return $this->next();
        }
        
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
