<?php

namespace App\Http\Livewire\Registration;

use Auth;
use Livewire\Component;
use App\Models\Registration;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Http\Livewire\Registration\Forms\HealthFormTrait;
use App\Http\Livewire\Registration\Forms\ParentFormTrait;
use App\Http\Livewire\Registration\Forms\AddressFormTrait;
use App\Http\Livewire\Registration\Forms\StudentFormTrait;
use App\Http\Livewire\Registration\Forms\MagisProgramTrait;
use App\Http\Livewire\Registration\Forms\EmergencyFormTrait;
use App\Http\Livewire\Registration\Forms\CoursePlacementTrait;
use App\Http\Livewire\Registration\Forms\AccommodationFormTrait;

class RegistrationForm extends Component implements HasForms
{
    use InteractsWithForms;

    # Traits
    use StudentFormTrait, AddressFormTrait, ParentFormTrait, HealthFormTrait, EmergencyFormTrait, AccommodationFormTrait, MagisProgramTrait, CoursePlacementTrait;

    # Model
    public Registration $registration;

    # Constant Variables
    const NotListed = 'Not Listed';
    
    public $data = [];

    public $done = false;

    public $open;

    public function render()
    {
        return view('livewire.registration.registration-form');
    }

    public function mount($uuid)
    {
        $registration = Registration::with('account', 'student')->whereUuid($uuid)->firstOrFail();

        $this->open = $registration->started_at ? true : false;
        
        $this->registration = $registration;
        $user = Auth::user();
        $accountId = accountId();
        
        $account = $registration->account->load('addresses', 'guardians', 'parents');

        $data = $this->registration->toArray();
        $data['student'] = $this->registration->student->toArray();
        $data['addresses'] = $account->addresses->toArray();
        $data['parents'] = $account->parents->toArray();
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
                ->content(new HtmlString('* The application saves your work automatically after you enter your information and click out of the text box. All required fields are  <span class="font-bold text-primary-red">red</span> and have an asterisk (<span class="text-primary-red">*</span>).')),
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
            Section::make('Health Information')
                ->schema($this->getHealthForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('Emergency Contact')
                ->schema($this->getEmergencyForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('School-based Accommodation')
                ->schema($this->getAccommodationForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('St. Ignatius Magis Program')
                ->schema($this->getMagisProgramForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('Course Placement')
                ->schema($this->getCoursePlacementForm())
                ->collapsible()
                ->collapsed(true),
        ];
    }

    protected function getFormStatePath(): string 
    {
        return 'data';
    }

    public function start()
    {
        $registration = $this->registration;
        $registration->started_at = now();
        $registration->save();

        return redirect(request()->header('Referer'));
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
}
