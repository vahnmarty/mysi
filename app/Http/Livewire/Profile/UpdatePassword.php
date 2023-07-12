<?php

namespace App\Http\Livewire\Profile;

use Auth;
use Hash;
use Closure;
use Livewire\Component;
use App\Rules\HasNumber;
use App\Rules\HasLowercase;
use App\Rules\HasUppercase;
use App\Rules\HasSpecialCharacter;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Phpsa\FilamentPasswordReveal\Password;
use Filament\Forms\Concerns\InteractsWithForms;

class UpdatePassword extends Component implements HasForms
{
    use InteractsWithForms;
    
    public $old_password, $password, $password_confirmation;

    // protected $messages = [
    //     'password.required' => 'Password is required',
    //     'password.min' => 'The password must be at least 8 characters',
    //     'password.max' => 'The password must not be greater than 16 characters',
    //     'password.confirmed' => 'Password and confirm password do not match.  Please re-enter password and confirm',
    //     'password_confirmation.required' => 'Confirm password is required',
    // ];

    public function render()
    {
        return view('livewire.profile.update-password');
    }

    protected function getFormSchema()
    {
        return [
            Password::make('old_password')
                ->label('Old Password')
                ->validationAttribute('Old Password')
                ->rules([
                    function () {
                        return function (string $attribute, $value, Closure $fail) {
                            if (!Hash::check($value, Auth::user()->password)) {
                                $fail("Old password is incorrect.");
                            }
                        };
                    },
                ])
                ->password()
                ->revealable()
                ->required(),
            Password::make('password')
                ->rules([
                    new HasUppercase(),
                    new HasLowercase(),
                    new HasNumber(),
                    new HasSpecialCharacter(),
                    function () {
                        return function (string $attribute, $value, Closure $fail) {
                            if (Auth::user()->checkPasswordTaken($value)) {
                                $fail("Please choose a different password. You cannot use your previous passwords.");
                            }
                        };
                    },
                ])
                ->minLength(8)
                ->maxLength(16)
                ->password()
                ->revealable()
                ->required()
                ->confirmed(),
            Password::make('password_confirmation')
                ->label("Confirm Password")
                ->password()
                ->revealable()
                ->required(),
        ];
    }

    public function update()
    {
        $data = $this->form->getState();

        $user = Auth::user();
        $user->password = Hash::make($data['password']);
        $user->save();

        $user->addPasswordHistory();

        Notification::make()
            ->title('Password changed successfully!')
            ->warning()
            ->send();

        $this->form->fill();
    }
}
