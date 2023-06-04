<?php

namespace App\Http\Livewire\Auth;

use Str;
use Auth;
use Hash;
use Closure;
use App\Models\User;
use App\Models\Account;
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

    public $status;
    protected $queryString = ['status'];
    
    public function render()
    {
        return view('livewire.auth.register-page')->layout('layouts.guest');
    }

    protected function getFormSchema()
    {
        return [
            TextInput::make('first_name')
                ->disableLabel()
                ->label('First Name')
                ->placeholder('Parent/Guardian First Name')
                ->maxLength(191)
                ->required(),
            TextInput::make('last_name')
                ->disableLabel()
                ->label('Last Name')
                ->placeholder('Parent/Guardian Last Name')
                ->maxLength(191)
                ->required(),
            // TextInput::make('username')
            //     ->disableLabel()
            //     ->unique(User::class, 'username')
            //     ->required(),
            TextInput::make('email')
                ->disableLabel()
                ->label('Email Address')
                ->placeholder('Parent/Guardian Email')
                ->email()
                ->unique(User::class, 'email')
                ->required(),
                TextInput::make('password')
                ->disableLabel()
                ->reactive()
                ->required()
                ->password()
                ->placeholder("Password")
                ->afterStateUpdated(function (Closure $get, $state) {
                    $this->validatePassword($state);
                }),
            TextInput::make('password_confirmation')
                ->disableLabel()
                ->label('Confirm Password')
                ->placeholder("Confirm Password")
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

        $account = $this->createAccount();

        $user = User::create([
            'account_id' => $account->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            //'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function createAccount()
    {
        # Fetch Salesforce

        $data = $this->form->getState();

        $name = $this->accountNameGenerator($data);

        return Account::create(['name' => $name]);
    }

    public function accountNameGenerator($data)
    {
        return 'The ' . $data['first_name'] . ' ' . $data['last_name'] . ' Family';

        // Examples:  The Joseph Smith Family (if only 1 parent); The Carol and Michael Brady Family (if 2 parents w/ same last name); The Albert Thomas and Zoey Anderson Family (if 2 parents w/ different last names) - NOTE:  Alphabetize by Parent's first name
    }
}
