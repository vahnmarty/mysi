<?php

namespace App\Http\Livewire\Auth;

use Str;
use Auth;
use Closure;
use App\Models\User;
use Livewire\Component;
use App\Notifications\Auth\PasswordChanged;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class ResetPasswordPage extends Component implements HasForms
{
    use InteractsWithForms;
    
    public $password, $password_confirmation;
    public $password_validation = [];
    public $email, $token;

    public function render()
    {
        return view('livewire.auth.reset-password-page');
    }

    public function mount($email, $token)
    {
        $this->email = $email;
        $this->token = $token;
        $this->validatePassword($this->password);
    }

    protected function getFormSchema()
    {
        return [
            TextInput::make('email')
                ->required()
                ->disabled()
                ->email(),
            TextInput::make('password')
                ->label('New Password')
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

        if($this->valid_password)
        {
            $user = User::where('email', $this->email)->first();
            $user->password = bcrypt($this->password);
            $user->save();

            Auth::login($user);

            $user->notify(new PasswordChanged);

            return redirect('dashboard');
        }

        Notification::make()
            ->title('Invalid password credentials. Check the validation rules.')
            ->danger()
            ->send();
        
    }
}
