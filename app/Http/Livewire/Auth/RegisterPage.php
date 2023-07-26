<?php

namespace App\Http\Livewire\Auth;

use Str;
use Auth;
use Hash;
use Closure;
use App\Models\User;
use App\Models\Account;
use Livewire\Component;
use App\Rules\HasNumber;
use App\Rules\UniqueEmail;
use App\Rules\HasLowercase;
use App\Rules\HasUppercase;
use App\Rules\PhoneNumberRule;
use App\Rules\HasSpecialCharacter;
use Illuminate\Support\HtmlString;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Actions\Action;
use Phpsa\FilamentPasswordReveal\Password;
use Illuminate\Validation\ValidationException;
use Filament\Forms\Concerns\InteractsWithForms;

class RegisterPage extends Component implements HasForms
{
    use InteractsWithForms;

    public $first_name, $last_name, $email, $password, $phone = '', $password_confirmation;

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
                ->validationAttribute('Parent/Guardian first name')
                ->label('First Name')
                ->placeholder('Parent/Guardian First Name')
                ->maxLength(191)
                ->required(),
            TextInput::make('last_name')
                ->disableLabel()
                ->validationAttribute('Parent/Guardian last name')
                ->label('Last Name')
                ->placeholder('Parent/Guardian Last Name')
                ->maxLength(191)
                ->required(),
            TextInput::make('phone')
                ->disableLabel()
                ->label('Parent/Guardian Phone')
                ->placeholder('Parent/Guardian Phone')
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000'))
                ->rules([new PhoneNumberRule])
                ->required(),
            TextInput::make('email')
                ->disableLabel()
                ->validationAttribute('Parent/Guardian email')
                ->label('Email Address')
                ->placeholder('Parent/Guardian Email')
                ->email()
                ->rules(['email:rfc,dns'])
                //->unique(table:User::class, column: 'email')
                ->lazy()
                ->afterStateUpdated(function(Closure $get){
                    // if(User::where('email', $get('email'))->exists()){
                    //     Notification::make()
                    //         ->title('Email taken')
                    //         ->body('This email already exists.')
                    //         ->actions([ 
                    //             Action::make('Forgot Password?')
                    //                 ->url(route('password.request'))
                    //         ])
                    //         ->danger()
                    //         ->send();
                    // }
                })
                ->required(),
            Placeholder::make('existing_email')
                ->label('')
                ->dehydrated(false)
                ->visible(fn(Closure $get) => User::where('email', $get('email'))->exists())
                ->reactive()
                ->content(fn() => new HtmlString('<p class="-my-2 text-sm">This email already exists. <a href="forgot-password" class="text-link">Forgot Password?</a></p>')),
            Password::make('password')
                ->disableLabel()
                ->validationAttribute('password')
                ->revealable()
                ->reactive()
                ->required()
                ->password()
                ->confirmed()
                ->minLength(8)
                ->maxLength(16)
                ->placeholder("Password")
                ->rules([
                    new HasUppercase(),
                    new HasLowercase(),
                    new HasNumber(),
                    new HasSpecialCharacter(),
                ]),
            Password::make('password_confirmation')
                ->disableLabel()
                ->revealable()
                ->label('Confirm Password')
                ->placeholder("Confirm Password")
                ->required()
                ->password()
        ];
    }


    protected function onValidationError(ValidationException $exception): void
    {
        
        Notification::make()
            //->title($exception->getMessage())
            ->title('Error! Please check the form.')
            ->danger()
            ->send();
    }

    public function register()
    {
        $data = $this->form->getState();

        if(User::where('email', $data['email'])->exists()){

            Notification::make()
            ->title('Error! This email already exists.')
            ->danger()
            ->send();

            return;
        }

        $account = $this->createAccount();

        $user = User::create([
            'account_id' => $account->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            //'username' => $data['username'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        $this->createParent($account, $data);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function createParent(Account $account, $data)
    {
        $account->parents()->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'mobile_phone' => $data['phone'],
            'personal_email' => $data['email'],
        ]);
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
