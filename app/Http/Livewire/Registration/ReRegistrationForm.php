<?php

namespace App\Http\Livewire\Registration;

use Auth;
use App\Models\Account;
use Livewire\Component;
use App\Models\ReRegistration;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Http\Livewire\Registration\Forms\DirectoryTrait;
use App\Http\Livewire\Registration\Forms\HealthFormTrait;
use App\Http\Livewire\Registration\Forms\ParentFormTrait;
use App\Http\Livewire\Registration\Forms\AddressFormTrait;
use App\Http\Livewire\Registration\Forms\SiblingFormTrait;
use App\Http\Livewire\Registration\Forms\StudentFormTrait;
use App\Http\Livewire\Registration\Forms\CoCurricularTrait;
use App\Http\Livewire\Registration\Forms\MagisProgramTrait;
use App\Http\Livewire\Registration\Forms\EmergencyFormTrait;
use App\Http\Livewire\Registration\Forms\FamilyDynamicsTrait;
use App\Http\Livewire\Registration\Forms\CoursePlacementTrait;
use App\Http\Livewire\Registration\Forms\AccommodationFormTrait;

class ReRegistrationForm extends Component implements HasForms
{
    use InteractsWithForms;

    # Traits
    use StudentFormTrait, AddressFormTrait, ParentFormTrait, HealthFormTrait, EmergencyFormTrait, AccommodationFormTrait, SiblingFormTrait, FamilyDynamicsTrait, DirectoryTrait;

    # Model
    public ReRegistration $registration;

    # Constant Variables
    const NotListed = 'Not Listed';
    
    public $data = [];

    public $done = false;

    public $open;

    protected $messages = [
        'data.other_primary_language_spoken.required' => 'Please enter a language.',
    ];

    public function render()
    {
        return view('livewire.registration.registration-form');
    }

    public function getAccount()
    {
        return Account::findOrFail(accountId());
    }

    public function getRecord()
    {
        return $this->registration;
    }

    public function mount($uuid)
    {
        $registration = ReRegistration::with('account', 'student')->whereUuid($uuid)->firstOrFail();
        $this->registration = $registration;
        
        $appStatus = '';//$this->getAppStatus();

        $this->open = true;
        
        
        $user = Auth::user();
        $accountId = accountId();
        
        $account = $registration->account->load('addresses', 'guardians', 'parents');
        
        $data = $this->registration->toArray();
        $data['student'] = $this->registration->student->toArray();
        $data['addresses'] = $account->addresses->toArray();
        $data['parents'] = $account->parents->toArray();
        $data['siblings'] = $account->children()->where('id', '!=', $this->registration->child_id)->get()->toArray();
        $data['parents_matrix'] = $this->getParentsMatrix();
        $data['family_dynamics'] = $this->getRelationshipMatrix();
        $data['siblings_matrix'] = $this->getSiblingsMatrix();
        $data['student_directory'] = [$this->registration->student->toArray()];
        $data['parents_directory'] = $account->parents->toArray();
        $data['primary_language_spoken'] = $account->primary_language_spoken;
        $data['other_primary_language_spoken'] = $account->other_primary_language_spoken;
        $data['autosave'] = true;

        $data['healthcare'] = $registration
            ->healthcare()
            ->firstOrCreate([ 
                'account_id' => $accountId
            ])
            ->toArray();
            
        $data['emergency_contact'] = $registration
            ->emergency_contact()
            ->firstOrCreate([ 
                'account_id' => $accountId
            ])
            ->toArray();

        $data['accommodation'] = $registration
            ->accommodation()
            ->firstOrCreate([ 
                'account_id' => $accountId
            ])
            ->toArray();

        $this->form->fill($data);
    }

    protected function getFormSchema(): array
    {
        return [
            Placeholder::make('form_description')
                ->label('')
                ->content(new HtmlString('* Please review the information below thoroughly, and update as necessary.  The application saves your work automatically after you enter your information and click out of the text box. All required fields are in <span class="font-bold text-primary-red">red</span>  and have an asterisk (<span class="text-primary-red">*</span>).')),
            Section::make('Student Information')
                ->collapsible()
                ->collapsed(true)
                ->schema($this->getStudentForm()),
            Section::make('Address Information')
                ->collapsible()
                ->collapsed(true)
                ->schema($this->getAddressForm()),
            Section::make('Parent/Guardian Information')
                ->schema($this->getParentForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('Sibling Information')
                ->schema($this->getSiblingForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('Family Dynamics')
                ->schema($this->getFamilyMatrix())
                ->collapsible()
                ->collapsed(true),
            Section::make('Health Information')
                ->schema($this->getHealthForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('Emergency Contact')
                ->schema($this->getEmergencyForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('School-Based Accommodation')
                ->schema($this->getAccommodationForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('SI Directory')
                ->schema($this->getSIDirectoryForm())
                ->collapsible()
                ->collapsed(true),
        ];
    }

    protected function getFormStatePath(): string 
    {
        return 'data';
    }

    public function refreshForm()
    {
        $this->refreshFormData(['parents_matrix']);
    }

    

    public function start()
    {
        $appStatus = $this->getAppStatus();
        $appStatus->registration_started = true;
        $appStatus->registration_start_date = now();
        $appStatus->save();

        return redirect(request()->header('Referer'));
    }

    public function autoSaveAccount($column, $value)
    {
        $account = Account::find(accountId());
        return $this->__autoSave($account, $column, $value);
    }

    public function __autoSave($model, $column, $value)
    {
        try {
            $model->$column = $value;
            $model->save();
            
        } catch (\Exception $e) {

            Notification::make()
                ->title('Error! Invalid value: ' . $value)
                ->danger()
                ->send();
        }
    }

    public function autoSave($column, $value, $relationship = null)
    {
        if($relationship){
            $model = $this->registration->{$relationship};
        }else{
            $model = $this->registration;
        } 

        if(is_array($value)){
            $value = implode(',', $value);
        }

        try {
            $model->$column = $value;
            $model->save();
        } catch (\Exception $e) {

            throw $e;
            
            Notification::make()
                ->title('System Error!')
                ->body('Please check error message (s) below the field.')
                ->danger()
                ->send();
        }

        
    }

    public function autoSaveFiles($column, $files, $relationship = null)
    {
        if($relationship){
            $model = $this->registration->$relationship;
        }else{
            $model = $this->registration;
        }
        
        $model->$column = $files;
        $model->save();
    }

    public function submit()
    {
        $data = $this->form->getState();

        $rereg = $this->registration;
        $rereg->completed_at = now();
        $rereg->save();

        dd('completed. tbd');
    }
}
