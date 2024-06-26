<?php

namespace App\Http\Livewire\Auth;

use Str;
use Auth;
use Hash;
use Closure;
use App\Models\User;
use App\Models\Account;
use App\Models\Parents;
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
use App\Rules\DoesntStartWith;

class RegisterPage extends Component implements HasForms
{
    use InteractsWithForms;

    public $first_name, $last_name, $email, $password, $phone = '', $password_confirmation;

    public $status;

    protected $queryString = ['status', 'email'];
    
    public function render()
    {
        return view('livewire.auth.register-page')->layout('layouts.guest');
    }

    public function mount()
    {
        if($this->email){
            $parent = Parents::where('personal_email', $this->email)->first();
            
            if($parent){
                $this->form->fill([
                    'first_name' => $parent->first_name,
                    'last_name' => $parent->last_name,
                    'phone' => $parent->mobile_phone,
                    'email' => $parent->personal_email
                ]);
            }else{
                $this->form->fill([
                    'email' => $this->email
                ]);
            }
            
        }
        
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
                ->validationAttribute('Phone Number')
                ->rules([new PhoneNumberRule, 'doesnt_start_with:1'])
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
                ->lazy()
                ->content(fn() => new HtmlString('<p class="-my-2 text-sm">This email already exists. <a href="forgot-password" class="text-link">Forgot Password?</a></p>')),
            Password::make('password')
                ->disableLabel()
                ->validationAttribute('password')
                ->revealable()
                ->lazy()
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

        $email = $data['email'];

        if(User::where('email', $email)->exists()){

            Notification::make()
            ->title('Error! This email already exists.')
            ->danger()
            ->send();

            return;
        }

        if( Parents::where('personal_email', $email)->exists()  ){
            $parent = Parents::with('account.users')->where('personal_email', $email)->first();
            $account = $parent->account;

            if($account->parents()->count() > 1)
            {
                if($account->users()->count()){
                    return redirect('login?email=' . $email . '&status=primary_parent');
                }else{
                    // Removed for Registration
                    //return redirect('login?email=' . $email . '&status=new_password');
                }
                
            }else{
                return redirect('login?email=' . $email . '&status=new_password&error=else');
            }
        }

        if(empty($account)){
            $account = $this->createAccount();
        }

        

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

        if(!empty($parent)){
            $parent->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'mobile_phone' => $data['phone'],
            ]);
        }else{
            $this->createParent($account, $data);
        }
        

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
            'is_primary' => true, // default

        ]);
    }

    public function createAccount()
    {
        # Fetch Salesforce

        $data = $this->form->getState();

        $name = $this->accountNameGenerator($data);

        return Account::create(['account_name' => $name]);
    }

    public function accountNameGenerator($data)
    {
        return 'The ' . $data['first_name'] . ' ' . $data['last_name'] . ' Family';

        // Examples:  The Joseph Smith Family (if only 1 parent); The Carol and Michael Brady Family (if 2 parents w/ same last name); The Albert Thomas and Zoey Anderson Family (if 2 parents w/ different last names) - NOTE:  Alphabetize by Parent's first name
    }
}
