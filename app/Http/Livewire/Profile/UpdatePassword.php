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
use Filament\Forms\Concerns\InteractsWithForms;

class UpdatePassword extends Component implements HasForms
{
    use InteractsWithForms;
    
    public $old_password, $password, $password_confirmation;

    public function render()
    {
        return view('livewire.profile.update-password');
    }

    protected function getFormSchema()
    {
        return [
            TextInput::make('old_password')
                ->rules([
                    function () {
                        return function (string $attribute, $value, Closure $fail) {
                            if (!Hash::check($value, Auth::user()->password)) {
                                $fail("The {$attribute} is invalid.");
                            }
                        };
                    },
                ])
                ->password()
                ->required(),
            TextInput::make('password')
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
                ->required(),
            TextInput::make('password_confirmation')
                ->label("Confirm Password")
                ->password()
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
