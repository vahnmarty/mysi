<?php

namespace App\Http\Livewire\Auth;

use Str;
use Auth;
use Closure;
use App\Models\User;
use App\Models\Parents;
use Livewire\Component;
use App\Rules\HasNumber;
use App\Rules\HasLowercase;
use App\Rules\HasUppercase;
use App\Models\AccountRequest;
use App\Rules\HasSpecialCharacter;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Phpsa\FilamentPasswordReveal\Password;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateAccountPassword extends Component implements HasForms
{
    use InteractsWithForms;

    public $email;
    public $password;
    public $valid_password;
    public $password_confirmation;
    public $password_validation = [];

    protected $messages = [
        'password.required' => 'Password is required',
        'password.min' => 'The password must be at least 8 characters',
        'password.max' => 'The password must not be greater than 16 characters',
        'password.confirmed' => 'Password and confirm password do not match.  Please re-enter password and confirm',
        'password_confirmation.required' => 'Confirm password is required',
    ];
    
    public function render()
    {
        return view('livewire.auth.create-account-password')->layout('layouts.guest');
    }

    public function mount($token)
    {
        $account = AccountRequest::where('token', $token)->firstOrFail();

        if($account->expired()){
            ## TODO
        }

        $this->email = $account->email;
    }

    protected function getFormSchema()
    {
        return [
            TextInput::make('email')
                ->required()
                ->disabled()
                ->email()
                ->rules(['email:rfc,dns']),
            Password::make('password')
                ->label('New Password')
                ->validationAttribute('Password')
                ->reactive()
                ->rules([
                    new HasUppercase(),
                    new HasLowercase(),
                    new HasNumber(),
                    new HasSpecialCharacter(),
                ])
                ->minLength(8)
                ->maxLength(16)
                ->password()
                ->revealable()
                ->required()
                ->confirmed(),
            Password::make('password_confirmation')
                ->label('Confirm Password')
                ->validationAttribute('')
                ->reactive()
                ->required()
                ->password()
                ->revealable()
        ];
    }


  

    public function submit()
    {
        $data = $this->form->getState();

        $parent = Parents::with('account')->where('personal_email', $data['email'])->first();

        if(!$parent){
            // TODO: Alert Error!
            return;
        }

        if(!$parent->account){
            // TODO: Alert Error
            return;
        }

        $user = new User;
        $user->account_id = $parent->account_id;
        $user->first_name = $parent->first_name;
        $user->last_name = $parent->last_name;
        $user->email = $data['email'];
        $user->email_verified_at = now();
        $user->password = $data['password'];
        $user->save();

        Auth::login($user);

        return redirect('dashboard');
    }
}
