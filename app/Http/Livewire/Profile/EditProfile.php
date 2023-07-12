<?php

namespace App\Http\Livewire\Profile;

use Str;
use Auth;
use Closure;
use App\Models\User;
use Livewire\Component;
use App\Rules\HasNumber;
use App\Rules\HasLowercase;
use App\Rules\HasUppercase;
use App\Models\EmailRequest;
use App\Rules\HasSpecialCharacter;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Notifications\Auth\ConfirmEmailRequest;
use Filament\Forms\Concerns\InteractsWithForms;

class EditProfile extends Component implements HasForms
{
    use InteractsWithForms;

    public $email_request = false;

    protected $messages = [
        'first_name.required' => 'First Name is required',
        'last_name.required' => 'Last Name is required',
        'phone.required' => 'Phone Number is required',
        'email.required' => 'Email is required',
        'email.email' => 'Enter a valid email address',
        'email.unique' => 'This email already exists'
    ];
    
    public function render()
    {
        return view('livewire.profile.edit-profile');
    }

    public function mount()
    {
        $this->profileForm->fill(Auth::user()->toArray());

        $this->email_request = EmailRequest::where('user_id', Auth::id())->whereNull('verified_at')->exists();
    }

    protected function getForms(): array 
    {
        return [
            'profileForm' => $this->makeForm()
                ->schema($this->getProfileFormSchema()),
            'emailForm' => $this->makeForm()
                ->schema($this->getEmailFormSchema()),
        ];
    } 

    protected function getProfileFormSchema()
    {
        return [
            TextInput::make('first_name')
                ->required(),
            TextInput::make('last_name')
                ->required(),
            TextInput::make('phone')
                ->placeholder('000-000-0000')
                ->tel()
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('000-000-0000'))
                ->required(),
        ];
    }

    protected function getEmailFormSchema()
    {
        return [
            TextInput::make('email')
                ->unique(table: User::class)
                ->email()
                ->rules(['email:rfc,dns'])
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
