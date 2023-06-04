<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\HtmlString;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class ForgotUsername extends Component implements HasForms
{
    use InteractsWithForms;

    public $first_name, $last_name, $email, $phone;

    public $sent = false;
    
    public function render()
    {
        return view('livewire.auth.forgot-username')->layout('layouts.guest');;
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
                ->required( fn() => empty($this->email) ),
        ];
    }

    public function submit()
    {
        $data = $this->form->getState();

        dd($data);
    }
}
