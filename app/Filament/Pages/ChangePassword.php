<?php

namespace App\Filament\Pages;

use Auth;
use Filament\Pages\Page;
use Filament\Facades\Filament;
use Filament\Pages\Actions\Action;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Placeholder;
use Phpsa\FilamentPasswordReveal\Password;
use App\Notifications\Admin\PasswordUpdated;
use Illuminate\Validation\ValidationException;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;

class ChangePassword extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static string $view = 'filament.pages.change-password';

    public $password, $password_confirmation;

    protected function getFormSchema() : array
    {
        return [
            Password::make('password')
                ->label('New Password')
                ->revealable()
                ->minLength(8)
                ->maxLength(16)
                ->required()
                ->confirmed(),
            Password::make('password_confirmation')
                ->label('Confirm Password')
                ->revealable()      
                ->required()
        ];
    }

    public function update()
    {
        $data = $this->form->getState();

        $user = Auth::user();
        $user->password = $data['password'];
        $user->save();

        $user->notify(new PasswordUpdated);

        // Notification::make()
        //     ->title("Your password has been updated successfully!")
        //     ->body('Login now using your new password.') 
        //     ->success()
        //     ->seconds(5)
        //     ->send();

        // $this->form->fill();

        // Auth::login($user);

        return redirect('admin');
    }
}
