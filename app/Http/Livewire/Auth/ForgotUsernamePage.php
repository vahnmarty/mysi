<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\HtmlString;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use App\Notifications\Auth\EmailForgotUsername;
use Filament\Forms\Concerns\InteractsWithForms;

class ForgotUsernamePage extends Component implements HasForms
{
    use InteractsWithForms;

    public $first_name, $last_name, $email, $phone = '';

    public $sent = false, $multiple;
    
    public function render()
    {
        return view('livewire.auth.forgot-username-page')->layout('layouts.guest');;
    }

    protected function getFormSchema()
    {
        return [
            TextInput::make('first_name')
                ->disableLabel()
                ->placeholder('First Name')
                ->autofocus()
                ->required(),
            TextInput::make('last_name')
                ->disableLabel()
                ->placeholder('Last Name')
                ->helperText(fn () => new HtmlString('<p class="mt-4 text-center">AND</p>'))
                ->required(),
            TextInput::make('email')
                ->disableLabel()
                ->placeholder("Email Address")
                ->validationAttribute("Email")
                ->helperText(fn () => new HtmlString('<p class="mt-4 text-center">OR</p>'))
                ->reactive()
                ->required( fn() =>  empty($this->phone) ),
            TextInput::make('phone')
                ->disableLabel()
                ->reactive()
                ->placeholder("Phone")
                ->required( fn() => empty($this->email) )
                ->tel()
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000')),
        ];
    }

    public function submit()
    {
        $data = $this->form->getState();

        $type = '';

        $query =  User::where('first_name', 'LIKE', '%' . $data['first_name'] . '%')
            ->where('last_name', 'LIKE', '%' . $data['last_name'] . '%');

        if($data['email']){
            $query->where('email', $data['email']);
            $type = 'email';
        }elseif($data['phone']) {
            $query->where('phone', $data['phone']);
            $type = 'phone';
        }

        $users = $query->get();

        if($users->count() >= 2){

            $this->multiple = true;

            return;
            
            // return Notification::make()
            //     ->title('Multiple Account')
            //     ->warning()
            //     ->body('The name and phone number is associated with multiple accounts.  Please contact **admissions@siprep.org** for assistance.')
            //     ->actions([
            //         Action::make('Contact')
            //             ->button()
            //             ->url('mailto:admissions@siprep.org') 
            //     ])
            //     ->send();
        }

        if(!$users->count()){
            return Notification::make()
                ->title('User not found.')
                ->danger()
                ->send();
        }

        $user = $users->first();

        $user->notify(new EmailForgotUsername($type));

        $this->sent = true;

        $this->email = $user->email;
        
    }
}
