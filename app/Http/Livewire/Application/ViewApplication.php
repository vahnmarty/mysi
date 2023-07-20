<?php

namespace App\Http\Livewire\Application;

use Closure;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\School;
use Livewire\Component;
use App\Enums\RacialType;
use App\Models\Application;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Concerns\InteractsWithForms;

class ViewApplication extends Component implements HasForms
{   
    use InteractsWithForms;

    public Application $app;

    public $data = [];
    
    public function render()
    {
        return view('livewire.application.view-application');
    }

    public function mount($uuid)
    {
        $this->app = Application::where('uuid', $uuid)->firstOrFail();

        $account = $this->app->account->load('addresses', 'guardians', 'parents', 'children', 'legacies');
        $user = Auth::user();

        $data = $this->app->toArray();
        $data['student'] = $this->app->student->toArray();
        $data['addresses'] = $account->addresses->toArray();
        $data['parents'] = $account->guardians->toArray();
        $data['parents_matrix'] = $account->parents->toArray();
        $data['siblings'] = $account->children()->where('id', '!=', $this->app->child_id)->get()->toArray();
        $data['siblings_matrix'] = $account->children()->where('id', '!=', $this->app->child_id)->get()->toArray();
        $data['legacy'] = $account->legacies->toArray();
        $data['activities'] = $this->app->activities->toArray();
        $data['autosave'] = true;
        $data['placement_test_date'] = settings('placement_test_date');

        if($this->app->payment){
            $this->amount = $this->app->payment?->final_amount;
        }

        $this->form->fill($data);
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Applicant Information')
                ->collapsible()
                ->schema($this->getStudentForm()),
            Section::make('Address Information')
                ->schema($this->getAddressForm())
                ->collapsible(),
            Section::make('Parent/Guardian Information')
                ->schema($this->getParentForm())
                ->collapsible(),
            Section::make('Sibling Information')
                ->schema($this->getSiblingForm())
                ->collapsible(),
            Section::make('Family Matrix')
                ->schema($this->getFamilyMatrix())
                ->collapsible()
                ->extraAttributes(['id' => 'matrix']),
            Section::make('Legacy Information')
                ->schema($this->getLegacyForm())
                ->collapsible(),
            Section::make('Spiritual and Community Information')
                ->schema($this->getReligionForm())
                ->collapsible(),
            Section::make('Parent/Guardian Statement')
                ->schema($this->getParentStatement())
                ->collapsible(),
            Section::make('Applicant Statement')
                ->schema($this->getStudentStatement())
                ->collapsible(),
            Section::make('School Activities')
                ->schema($this->getActivityForm())
                ->collapsible(),
            Section::make('Writing Sample')
                ->schema($this->getWritingSampleForm())
                ->collapsible(),
            Section::make('High School Placement Test')
                ->schema($this->getPlacementForm())
                ->collapsible(),
            Section::make('Final Steps')
                ->schema($this->getFinalStepsForm())
                ->collapsible(),
        ];
    }

    protected function getFormModel() 
    {
        return $this->app;
    }

    protected function getFormStatePath(): string 
    {
        return 'data';
    } 

    public function getStudentForm()
    {
        return [
            TextInput::make('student.first_name')
                ->label('Legal First Name')
                ->disabled(),
            TextInput::make('student.middle_name')
                ->label('Legal Middle Name')
                ->disabled(),
            TextInput::make('student.last_name')
                ->label('Legal Last Name')
                ->disabled(),
            Select::make('student.suffix')
                ->options(Suffix::asSameArray())
                ->label('Suffix')
                ->disabled(),
            TextInput::make('student.preferred_first_name')
                ->label('Preferred First Name (Must be different from Legal First Name)')
                ->disabled(),
            DatePicker::make('student.birthdate')
                ->label('Date of Birth')
                ->disabled(),
            Select::make('student.gender')
                ->label('Gender')
                ->options(Gender::asSelectArray())
                ->disabled(),
            TextInput::make('student.personal_email')
                ->email()
                ->rules(['email:rfc,dns'])
                ->label('Personal Email')
                ->disabled(),
            TextInput::make('student.mobile_phone')
                ->label('Mobile Phone')
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000'))
                ->disabled(),
            CheckboxList::make('student.race')
                ->label(new HtmlString('<legend>How do you identify racially?</legend><div class="text-xs" style="font-weight: 500">*Select all that apply to you.</div>'))
                ->options(RacialType::asSameArray())
                ->columns(3)
                ->lazy()
                ->afterStateHydrated(function (CheckboxList $component, $state) {
                    if(is_string($state)){
                        $component->state(explode(',', $state));
                    }else{
                        $data = is_array($state) ? $state : [];
                        $component->state($data);
                    }
                })
                ->disabled(),
            TagsInput::make('student.ethnicity')
                ->label(new HtmlString('<legend>What is your ethnicity?</legend><div class="text-xs" style="font-weight: 500">*If more than one, separate ethnicities with a comma.</div>'))
                ->helperText('EXAMPLE: "Filipino, Hawaiian, Irish, Italian, Eritrean, Armenian, Salvadorian"')
                ->lazy()
                ->placeholder('')
                ->afterStateHydrated(function (TagsInput $component, $state) {
                    if(is_string($state)){
                        $component->state(explode(',', $state));
                    }else{
                        $data = is_array($state) ? $state : [];
                        $component->state($data);
                    }
                })
                ->disabled(),
            Select::make('student.current_school')
                ->label('Current School')
                ->options(['Not Listed' => 'Not Listed'] + School::active()->get()->pluck('name', 'name')->toArray())
                ->preload()
                ->disabled(),
            TextInput::make('student.current_school_not_listed')
                ->label('If not listed, add it here')
                ->disabled(),
            Select::make('other_high_school_1')
                ->label('Other High School #1')
                ->options(['Not Listed' => 'Not Listed'] + School::active()->get()->pluck('name', 'name')->toArray() )
                ->preload()
                ->disabled(),
            Select::make('other_high_school_2')
                ->label('Other High School #2')
                ->options(['Not Listed' => 'Not Listed'] + School::active()->get()->pluck('name', 'name')->toArray() )
                ->preload()
                ->disabled(),
            Select::make('other_high_school_3')
                ->label('Other High School #3')
                ->options(['Not Listed' => 'Not Listed'] + School::active()->get()->pluck('name', 'name')->toArray() )
                ->preload()
                ->disabled(),
            Select::make('other_high_school_4')
                ->label('Other High School #4')
                ->options(['Not Listed' => 'Not Listed'] + School::active()->get()->pluck('name', 'name')->toArray() )
                ->preload()
                ->disabled(),
        ];
    }

    public function getAddressForm()
    {
        return [

        ];
    }

    public function getParentForm()
    {
        return [

        ];
    }

    public function getSiblingForm()
    {
        return [

        ];
    }

    public function getFamilyMatrix()
    {
        return [

        ];
    }

    public function getLegacyForm()
    {
        return [

        ];
    }

    public function getReligionForm()
    {
        return [

        ];
    }

    public function getParentStatement()
    {
        return [

        ];
    }

    public function getStudentStatement()
    {
        return [

        ];
    }

    public function getActivityForm()
    {
        return [

        ];
    }

    public function getWritingSampleForm()
    {
        return [

        ];
    }

    public function getPlacementForm()
    {
        return [

        ];
    }

    public function getFinalStepsForm()
    {
        return [

        ];
    }
}
