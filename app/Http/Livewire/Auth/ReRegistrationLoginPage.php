<?php

namespace App\Http\Livewire\Auth;

use Auth;
use Mail;
use App\Models\User;
use App\Models\Child;
use App\Models\Account;
use App\Models\Parents;
use Livewire\Component;
use App\Enums\AccountAction;
use App\Mail\AccountRequested;
use App\Mail\SetupNewPassword;
use App\Models\AccountRequest;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Placeholder;
use Phpsa\FilamentPasswordReveal\Password;
use Filament\Forms\Concerns\InteractsWithForms;

class ReRegistrationLoginPage extends Component implements HasForms
{
    use InteractsWithForms;

    public $email, $parent;

    public $parents = [];

    public $is_existing, $is_invalid, $for_register;

    public $user;

    public function render()
    {
        return view('livewire.auth.re-registration-login-page')->layout('layouts.guest');
    }

    public function mount()
    {
        $this->form->fill();
    }

    protected function getFormSchema()
    {
        return [
            TextInput::make('email')
                ->label('')
                ->validationAttribute('email')
                ->placeholder('SI email address')
                ->lazy()
                ->autofocus()
                ->required(),
            Select::make('parent')
                ->label('Choose the parent information we will use to create the account.')
                ->required()
                ->visible(fn() => $this->is_existing && !$this->user)
                ->options(fn() => $this->parents)
        ];
    }

    public function next()
    {
        $data  = $this->form->getState();

        if(!empty($data['parent'])){

            $parent = Parents::find($data['parent']);

            if(User::where('email', $parent->personal_email)->exists()){
                return redirect()->to('login?email=' . $parent->personal_email);
            }

            return redirect()->to('register?email=' . $parent->personal_email);
            

        }else{
            $child = $this->checkIfExistingChildren($data['email']);

            if($child){
                $this->is_existing = true;
                $this->setParents($child->account_id);
                $this->dispatchBrowserEvent('redirect-login');
            }else{
                $this->is_invalid = true;
                $this->dispatchBrowserEvent('redirect-login');
            }
        }
        
        
    }

    public function setParents($accountId)
    {
        $account = Account::find($accountId);
        $this->parents = Parents::where('account_id', $accountId)->get()->pluck('full_name', 'id')->toArray();

        if($account->user){
            $this->user = $account->user;
        }else{
            $this->for_register = true;
        }
    }

    public function checkIfExistingChildren($email)
    {
        return Child::where('si_email', $email)->first();
    }

    public function createAccount()
    {
        $data = $this->form->getState();

        if(!empty($data['parent'])){

            $parent = Parents::find($data['parent']);

            if(User::where('email', $parent->personal_email)->exists()){
                return redirect()->to('login?email=' . $parent->personal_email);
            }

            return redirect()->to('register?email=' . $parent->personal_email);

        }

        return redirect('register?email=' . $data['email']);
    }
}
