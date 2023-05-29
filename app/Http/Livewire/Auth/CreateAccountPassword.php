<?php

namespace App\Http\Livewire\Auth;

use Str;
use Auth;
use Closure;
use App\Models\User;
use Livewire\Component;
use App\Models\AccountRequest;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateAccountPassword extends Component implements HasForms
{
    use InteractsWithForms;

    public $email;
    public $password;
    public $valid_password;
    public $password_confirmation;
    public $password_validation = [];
    
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


        $this->validatePassword($this->password);
    }

    protected function getFormSchema()
    {
        return [
            TextInput::make('password')
                ->reactive()
                ->required()
                ->password()
                ->afterStateUpdated(function (Closure $get, $state) {
                    $this->validatePassword($state);
                }),
            TextInput::make('password_confirmation')
                ->label('Confirm Password')
                ->reactive()
                ->required()
                ->password()
                ->afterStateUpdated(function (Closure $get, $state) {
                    $this->validatePassword($get('password'));
                })
        ];
    }


    public function validatePassword($password = null)
    {
        $array = [
            [
                'key' => 'uppercase',
                'passed' => preg_match('/[A-Z]/', $password),
                'description' => 'At least 1 uppercase letter',
            ],
            [
                'key' => 'lowercase',
                'passed' =>  preg_match('/[a-z]/', $password),
                'description' => 'At least 1 lowercase letter',
            ],
            [
                'key' => 'number',
                'passed' => preg_match('/[0-9]/', $password),
                'description' => 'At least 1 number',
            ],
            [
                'key' => 'special',
                'passed' => preg_match('/[!@#$%]/', $password),
                'description' => 'At least 1 special character (only use the following characters: ! @ # $ or %)',
            ],
            [
                'key' => 'characters',
                'passed' => (Str::length($password) >= 8 && Str::length($password) <= 16),
                'description' => 'Must be between 8 – 16 characters long'
            ],
            [
                'key' => 'confirmed',
                'passed' => $this->password_confirmation == $password,
                'description' => 'Password must match with confirm password.'
            ]
        ];

        $this->valid_password = true;

        foreach($array as $item)
        {
            if($item['passed'] == false){
                $this->valid_password = false;
                break;
            }
        }

        $this->password_validation = $array;
    }

    public function submit()
    {
        $data = $this->form->getState();

        // Fetch Salesforce: Name, Student Info
        // Create User Model

        $arr = explode('@', $this->email);

        $user = new User;
        $user->name = $arr[0];
        $user->email = $this->email;
        $user->email_verified_at = now();
        $user->password = bcrypt($this->password);
        $user->save();

        Auth::login($user);

        return redirect('dashboard');
    }
}
