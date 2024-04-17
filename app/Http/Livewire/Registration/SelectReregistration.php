<?php

namespace App\Http\Livewire\Registration;

use Mail;
use Closure;
use App\Models\Child;
use Livewire\Component;
use App\Enums\GradeLevel;
use App\Models\ReRegistration;
use App\Mail\DeclinedReRegistration;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class SelectReregistration extends Component implements HasForms
{
    use InteractsWithForms;

    public Child $child;

    public $si_attending, $confirm;

    protected $messages = [
        'confirm.accepted' => 'This field is required'
    ];
    
    public function render()
    {
        return view('livewire.registration.select-reregistration');
    }

    public function mount($id)
    {
        $child = Child::findOrFail($id);
        
        $this->valid = $this->checkValid($child);

        $this->child = $child;

        $this->form->fill();
    }

    public function getFormSchema()
    {
        return [
            Select::make('si_attending')
                ->label('Will you be attending St. Ignatius in '. app_variable('academic_school_year') .'?')
                ->options([
                    1 => 'Yes',
                    0 => 'No',
                ])
                ->reactive()
                ->required(),
            TextInput::make('transfer_school')
                ->label('Name of School Transferring To')
                ->visible(fn(Closure $get) => $get('si_attending') == '0'),
            Checkbox::make('confirm')
                ->label('I confirm my decision to decline re-registration.')
                ->reactive()
                ->visible(fn(Closure $get) => $get('si_attending') == '0')
                ->required()
                ->accepted()
        ];
    }

    public function submit()
    {
        $data = $this->form->getState();

        $child = $this->child;
        
        if($data['si_attending'] == '0'){
            $reg = new ReRegistration;
            $reg->account_id = accountId();
            $reg->attending_si = false;
            $reg->transfer_school = $data['transfer_school'];
            $reg->child_id = $child->id;
            $reg->save();

            Mail::to('mysi_admin@siprep.org')->send(new DeclinedReRegistration($reg));

            return redirect('reregistration');
        }

        $registration = $child->reregistration;

        if(!$registration){
            $registration = new ReRegistration;
            $registration->account_id = accountId();
            $registration->attending_si = true;
            $registration->child_id = $child->id;
            $registration->started_at = now();
            $registration->save();
        }

        return redirect()->route('registration.re.form', $registration->uuid);
    }

    public function checkValid(Child $child)
    {
        $grades = [GradeLevel::Freshman, GradeLevel::Sophomore, GradeLevel::Junior];
        $schools = [ 'St. Ignatius College Preparatory'];
        
        return in_array($child->current_school, $schools) && in_array($child->current_grade, $grades);
    }
}
