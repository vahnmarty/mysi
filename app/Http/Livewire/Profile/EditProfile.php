<?php

namespace App\Http\Livewire\Profile;

use Str;
use Auth;
use App\Models\User;
use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Notifications\Auth\ConfirmEmailRequest;
use Filament\Forms\Concerns\InteractsWithForms;

class EditProfile extends Component implements HasForms
{
    use InteractsWithForms;

    public $email_request = false;
    
    public function render()
    {
        return view('livewire.profile.edit-profile');
    }

    public function mount()
    {
        $this->profileForm->fill(Auth::user()->toArray());
    }

    protected function getForms(): array 
    {
        return [
            'profileForm' => $this->makeForm()
                ->schema($this->getProfileFormSchema()),
            'emailForm' => $this->makeForm()
                ->schema($this->getEmailFormSchema()),
            'passwordForm' => $this->makeForm()
                ->schema($this->getPasswordFormSchema()),
        ];
    } 

    protected function getProfileFormSchema()
    {
        return [
            TextInput::make('first_name')
                ->required(),
            TextInput::make('last_name')
                ->required(),
        ];
    }

    protected function getEmailFormSchema()
    {
        return [
            TextInput::make('email')
                ->unique(table: User::class)
                ->email()
                ->required(),
        ];
    }

    protected function getPasswordFormSchema()
    {
        return [
            TextInput::make('old_password')
                ->password()
                ->required(),
            TextInput::make('new_password')
                ->password()
                ->required(),
            TextInput::make('password_confirmation')
                ->label("Confirm Password")
                ->password()
                ->required(),
        ];
    }

    public function updateProfile()
    {
        $data = $this->profileForm->getState();

        Auth::user()->update($data);

        Notification::make()
            ->title('Profile updated successfully!')
            ->success()
            ->send();
    }

    public function updateEmail()
    {
        $data = $this->emailForm->getState();

        $user = Auth::user();

        $email = $user->emailRequests()->firstOrCreate([
            'email' => $data['email'],
        ], [
            'token' => Str::random(32)
        ]);

        $user->notify(new ConfirmEmailRequest($email));

        $this->email_request = true;

        Notification::make()
            ->title('New Email Request. Confirm your email!')
            ->warning()
            ->send();
    }
}
