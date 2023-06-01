<?php

namespace App\Http\Livewire\Auth;

use Str;
use Auth;
use Hash;
use Closure;
use App\Models\User;
use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use Filament\Forms\Concerns\InteractsWithForms;

class RegisterPage extends Component implements HasForms
{
    use InteractsWithForms;

    public $first_name, $last_name;
    public $username, $email;
    public $password, $valid_password, $password_confirmation, $password_validation = [];

    protected $queryString = ['status'];
    
    public function render()
    {
        return view('livewire.auth.register-page')->layout('layouts.guest');
    }

    protected function getFormSchema()
    {
        return [
            TextInput::make('first_name')
                ->label('First Name')
                ->placeholder('Parent/Guardian First Name')
                ->maxLength(191)
                ->required(),
            TextInput::make('last_name')
                ->label('Last Name')
                ->placeholder('Parent/Guardian Last Name')
                ->maxLength(191)
                ->required(),
            TextInput::make('username')
                ->unique(User::class, 'username')
                ->required(),
            TextInput::make('email')
                ->label('Email Address')
                ->placeholder('Enter your email address')
                ->email()
                ->unique(User::class, 'email')
                ->required(),
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

    protected function onValidationError(ValidationException $exception): void
    {
        Notification::make()
            ->title($exception->getMessage())
            ->danger()
            ->send();
    }

    public function register()
    {
        $data = $this->form->getState();

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
