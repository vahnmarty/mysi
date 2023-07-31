<?php

namespace App\Http\Livewire\Application;

use Closure;
use Carbon\Carbon;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\School;
use App\Models\Address;
use Livewire\Component;
use App\Enums\GradeLevel;
use App\Enums\ParentType;
use App\Enums\RacialType;
use App\Enums\Salutation;
use App\Enums\AddressType;
use App\Enums\SiblingType;
use App\Enums\CommonOption;
use App\Enums\ParentSuffix;
use App\Enums\ReligionType;
use App\Models\Application;
use App\Enums\EmploymentStatus;
use App\Enums\LivingSituationType;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Auth;
use App\Enums\FamilySpiritualityType;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use App\Forms\Components\WordTextArea;
use App\Models\Parents as ParentModel;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use App\Forms\Components\ReadonlyRadio;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Concerns\InteractsWithForms;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;

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
        $this->app = Application::with('archive')->where('uuid', $uuid)->firstOrFail();

        $archive = $this->app->archive;

        $data = $archive->application;
        $data['student'] = $archive->student;
        $data['addresses'] = $archive->addresses;
        $data['parents'] = $archive->parents;
        $data['parents_matrix'] = $archive->parents_matrix;
        $data['siblings'] = $archive->siblings;
        $data['siblings_matrix'] =$archive->siblings_matrix;
        $data['legacy'] = $archive->legacy;
        $data['activities'] = $archive->activities;
        $data['placement_test_date'] = settings('placement_test_date');


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
            Section::make('Family Dynamics')
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
                ->required()
                ->disabled(),
            TextInput::make('student.middle_name')
                ->label('Legal Middle Name')
                ->disabled(),
            TextInput::make('student.last_name')
                ->label('Legal Last Name')
                ->required()
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
                ->required()
                ->disabled(),
            Select::make('student.gender')
                ->label('Gender')
                ->options(Gender::asSelectArray())
                ->required()
                ->disabled(),
            TextInput::make('student.personal_email')
                ->email()
                ->rules(['email:rfc,dns'])
                ->label('Personal Email')
                ->required()
                ->disabled(),
            TextInput::make('student.mobile_phone')
                ->label('Mobile Phone')
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000'))
                ->required()
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
                ->optionsLimit(50)
                ->required()
                ->disabled(),
            TextInput::make('student.current_school_not_listed')
                ->label('If not listed, add it here')
                ->visible(fn(Closure $get) => $get('student.current_school') == 'Not Listed')
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
                ->optionsLimit(50)
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
                ->optionsLimit(50)
                ->disabled(),
        ];
    }

    public function getAddressForm()
    {
        return [
            Repeater::make('addresses')
                ->label('')
                ->disableItemCreation()
                ->disableItemMovement()
                ->disableItemDeletion()
                ->disableItemMovement()
                ->schema([
                    Select::make('address_type')
                        ->label('Address Type')
                        ->options(function(Closure $get){
                            $current = Address::where('account_id', accountId())->where('id', '!=', $get('id'))->pluck('address_type', 'address_type')->toArray();
                            $array = AddressType::asSameArray();
                            $types = [];

                            foreach($array as $type)
                            {
                                if(!in_array($type, $current)){
                                    $types[] = $type;
                                }
                            }
                            return array_combine($types, $types);
                        })
                        ->required()
                        ->disabled(),
                    TextInput::make('address')
                        ->label('Address')
                        ->required()
                        ->disabled(),
                    TextInput::make('city')
                        ->label('City')
                        ->required()
                        ->disabled(),
                    Select::make('state')
                        ->label('State')
                        ->options(us_states())
                        ->required()
                        ->disabled(),
                    TextInput::make('zip_code')
                        ->label('ZIP Code')
                        ->required()
                        ->disabled(),
                    TextInput::make('phone_number')
                        ->label('Phone Number')
                        ->disabled(),
                ])
        ];
    }

    public function getParentForm()
    {
        return [
            Repeater::make('parents')
                ->label('')
                ->disableItemCreation()
                ->disableItemMovement()
                ->disableItemDeletion()
                ->disableItemMovement()
                ->schema([
                    Select::make('salutation')
                        ->options(Salutation::asSameArray())
                        ->required()
                        ->disabled(),
                    TextInput::make('first_name')
                        ->label('Legal First Name')
                        ->required()
                        ->disabled(),
                    TextInput::make('middle_name')
                        ->label('Legal Middle Name')
                        ->disabled(),
                    TextInput::make('last_name')
                        ->label('Legal Last Name')
                        ->required()
                        ->disabled(),
                    Select::make('suffix')
                        ->label('Suffix')
                        ->options(ParentSuffix::asSameArray())
                        ->preload()
                        ->disabled(),
                    TextInput::make('mobile_phone')
                        ->label('Mobile Phone')
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000'))
                        ->afterStateHydrated(function(Closure $set, $state){
                            if(!$state){
                                $set('mobile_phone', '');
                            }
                        })
                        ->required()
                        ->disabled(),
                    TextInput::make('personal_email')
                        ->label('Preferred Email')
                        ->required()
                        ->disabled(),
                    Select::make('employment_status')
                        ->label('What is your employment status?')
                        ->disabled()
                        ->options(EmploymentStatus::asSameArray()),
                    TextInput::make('employer')
                        ->label('Employer')
                        ->disabled(),
                    TextInput::make('job_title')
                        ->label('Job Title')
                        ->disabled(),
                    TextInput::make('work_email')
                        ->label('Work Email')
                        ->disabled(),
                    TextInput::make('work_phone')
                        ->label('Work Phone')
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000'))
                        ->afterStateHydrated(function(Closure $set, $state){
                            if(!$state){
                                $set('work_phone', '');
                            }
                        })
                        ->disabled(),
                    TextInput::make('work_phone_ext')
                        ->label('Work Phone Extension')
                        ->disabled(),
                    WordTextArea::make('schools_attended')
                        ->label('List all high schools, colleges, or graduate schools you have attended')
                        ->disabled(),
                ])
        ];
    }

    public function getSiblingForm()
    {
        return [
            Repeater::make('siblings')
                ->label('')
                ->disableItemCreation()
                ->disableItemMovement()
                ->disableItemDeletion()
                ->disableItemMovement()
                ->schema([
                    TextInput::make('first_name')
                        ->label('Legal First Name')
                        ->required()
                        ->disabled(),
                    TextInput::make('middle_name')
                        ->label('Legal Middle Name')
                        ->disabled(),
                    TextInput::make('last_name')
                        ->label('Legal Last Name')
                        ->required()
                        ->disabled(),
                    Select::make('suffix')
                        ->options(Suffix::asSameArray())
                        ->label('Suffix')
                        ->preload()
                        ->disabled(),
                    Select::make('current_school')
                        ->label('Current School')
                        ->options(School::active()->get()->pluck('name', 'name')->toArray() + ['Not Listed' => 'Not Listed'])
                        ->preload()
                        ->optionsLimit(50)
                        ->required()
                        ->disabled(),
                    TextInput::make('current_school_not_listed')
                        ->label('If not listed, add it here')
                        ->hidden(fn (Closure $get) => $get('current_school') !== 'Not Listed')
                        ->required()
                        ->disabled(),
                    Select::make('current_grade')
                        ->label('Current Grade')
                        ->options(GradeLevel::asSameArray())
                        ->preload()
                        ->required()
                        ->disabled(),
                    Radio::make('attended_at_si')
                        ->label('Attended high school at SI?')
                        ->options([
                            0 => 'No',
                            1 => 'Yes'
                        ])
                        ->visible(fn(Closure $get) => $get('current_grade') == GradeLevel::College || $get('current_grade') == GradeLevel::PostCollege)
                        ->required()
                        ->disabled(),
                    TextInput::make('graduation_year')
                        ->label('High School Graduation Year')
                        ->required()
                        ->disabled(),
                ])
                
            
        ];
    }

    public function getFamilyMatrix()
    {
        return [
            TableRepeater::make('parents_matrix')
                ->label('')
                ->disableItemCreation()
                ->disableItemDeletion()
                ->disableItemMovement()
                ->hideLabels()
                ->columnSpan('full')
                ->schema([
                    Hidden::make('id')->reactive(),
                    Hidden::make('first_name')->reactive(),
                    Hidden::make('last_name')->reactive(),
                    TextInput::make('full_name')
                        ->label('Parent/Guardian')
                        ->afterStateHydrated(function(Closure $get, Closure $set){
                            $set('full_name', $get('first_name') . ' ' . $get('last_name'));
                        })
                        ->reactive()
                        ->disabled()
                        ->disableLabel(),   
                    Select::make('relationship_type')
                        ->label('Relationship to Applicant')
                        ->disableLabel()
                        ->options(ParentType::asSameArray())
                        ->disabled(),
                    Select::make('address_location')
                        ->label('Address Location')
                        ->disableLabel()
                        ->options(function(){
                            return Address::where('account_id', accountId())->pluck('address_type', 'address_type')->toArray();
                        })
                        ->disabled(),
                    Select::make('living_situation')
                        ->label('Living Situation')
                        ->disableLabel()
                        ->options(LivingSituationType::asSameArray())
                        ->disabled(),
                    Toggle::make('deceased_flag')
                        ->label('Deceased?'),
                ]),
            TableRepeater::make('siblings_matrix')
                ->label('')
                ->disableItemCreation()
                ->disableItemDeletion()
                ->disableItemMovement()
                ->hideLabels()
                ->columnSpan('full')
                ->schema([
                    Hidden::make('id')->reactive(),
                    Hidden::make('first_name')->reactive(),
                    Hidden::make('last_name')->reactive(),
                    TextInput::make('full_name')
                        ->label('Sibling')
                        ->disabled()
                        ->disableLabel()
                        ->afterStateHydrated(function(Closure $get, Closure $set){
                            $set('full_name', $get('first_name') . ' ' . $get('last_name'));
                        }),
                    Select::make('relationship_type')
                        ->label('Relationship to Applicant')
                        ->disableLabel()
                        ->options(SiblingType::asSameArray())
                        ->disabled(),
                    Select::make('address_location')
                        ->label('Address Location')
                        ->disableLabel()
                        ->options(function(){
                            return Address::where('account_id', accountId())->pluck('address_type', 'address_type')->toArray();
                        })
                        ->disabled(),
                    Select::make('living_situation')
                        ->label('Living Situation')
                        ->disableLabel()
                        ->options(LivingSituationType::asSameArray())
                        ->disabled(),
                    Placeholder::make('blank_deceased')
                        ->label(new HtmlString('<span class="invisible">*Deceased</span>'))
                        ->content('')
                        ->disabled()
                ])
        ];
    }

    public function getLegacyForm()
    {
        return [
            Repeater::make('legacy')
                ->label('')
                ->disableItemCreation()
                ->disableItemDeletion()
                ->disableItemMovement()
                ->schema([
                    TextInput::make('first_name')
                        ->label('First Name')
                        ->required()
                        ->disabled(),
                    TextInput::make('last_name')
                        ->label('Last Name')
                        ->required()
                        ->disabled(),
                    Select::make('relationship_type')
                        ->label('Relationship to Applicant')
                        ->options(ParentType::asSameArray())
                        ->required()
                        ->disabled(),
                    TextInput::make('graduation_year')
                        ->label('Graduation Year')
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('0000'))
                        ->required()
                        ->disabled(),
            ])
            
        ];
    }

    public function getReligionForm()
    {
        return [
            
            Select::make('student.religion')
                ->options(ReligionType::asSelectArray())
                ->label("Applicant's Religion")
                ->required()
                ->disabled(),
            TextInput::make('student.religion_other')
                ->label('If "Other," add it here')
                ->disabled(),
            TextInput::make('student.religious_community')
                ->label('Church/Faith Community')
                ->disabled(),
            TextInput::make('student.religious_community_location')
                ->label('Church/Faith Community Location')
                ->disabled(),
            TextInput::make('student.baptism_year')
                ->label('Baptism Year')
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('0000'))
                ->disabled(),
            TextInput::make('student.confirmation_year')
                ->label('Confirmation Year')
                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('0000'))
                ->disabled(),
            WordTextArea::make('impact_to_community')
                ->label("What impact does community have in your life and how do you best support your child's school community?")
                ->rows(5)
                ->required()
                ->disabled(),
            CheckboxList::make('describe_family_spirituality')
                ->label(new HtmlString("How would you describe your family's spirituality? <p class='text-sm text-gray-900'>Check all that apply.</p> "))
                ->options(FamilySpiritualityType::asSameArray())
                ->columns(3)
                ->afterStateHydrated(function (CheckboxList $component, $state) {
                    if(is_string($state)){
                        $component->state(explode(',', $state));
                    }else{
                        $data = is_array($state) ? $state : [];
                        $component->state($data);
                    }
                })
                ->required()
                ->disabled(),
            WordTextArea::make('describe_family_spirituality_in_detail')
                ->label("Describe the practice(s) checked above in more detail")
                ->helperText("Please limit your answer to 75 words.")
                ->required()
                ->rows(5)
                ->required()
                ->disabled(),
            Fieldset::make('Will you encourage your child to proactively participate in the following activities?')
                ->columns(3)
                ->schema([
                    Select::make('religious_studies_classes')
                        ->options(CommonOption::asSelectArray())
                        ->label("Religious Studies Classes")
                        ->required()
                        ->disabled(),
                    Textarea::make('religious_studies_classes_explanation')
                        ->label('If No/Unsure, please explain. Please limit your answer to 30 words.')
                        ->columnSpan(2)
                        ->rows(3)
                        ->disabled(),
                    Select::make('school_liturgies')
                        ->options(CommonOption::asSelectArray())
                        ->label("School Liturgies")
                        ->required()
                        ->disabled(),
                    WordTextArea::make('school_liturgies_explanation')
                        ->label('If No/Unsure, please explain. Please limit your answer to 30 words.')
                        ->columnSpan(2)
                        ->rows(3)
                        ->disabled(),
                    Select::make('retreats')
                        ->options(CommonOption::asSelectArray())
                        ->label("Retreats")
                        ->required()
                        ->disabled(),
                    WordTextArea::make('retreats_explanation')
                        ->label('If No/Unsure, please explain. Please limit your answer to 30 words.')
                        ->columnSpan(2)
                        ->rows(3)
                        ->disabled(),
                    Select::make('community_service')
                        ->options(CommonOption::asSelectArray())
                        ->label("Community Service")
                        ->required()
                        ->disabled(),
                    WordTextArea::make('community_service_explanation')
                        ->label('If No/Unsure, please explain. Please limit your answer to 30 words.')
                        ->columnSpan(2)
                        ->rows(3)
                        ->disabled(),
                ]),

                TextInput::make('religious_statement_by')
                    ->label('Religious Form Submitted By')
                    ->required()
                    ->disabled(),
                Select::make('religious_relationship_to_student')
                    ->label('Relationship to Applicant')
                    ->options(ParentType::asSameArray())
                    ->required()
                    ->disabled()
            
        ];
    }

    public function getParentStatement()
    {
        return [
            Placeholder::make('section_parent_statement')
                ->label('')
                ->content(new HtmlString('* This section is to be completed by a parent/guardian only.')),
            WordTextArea::make('why_si_for_your_child')
                ->label("Why do you want your child to attend St. Ignatius College Preparatory?")
                ->helperText("Please limit your answer to 75 words.")
                ->required()
                ->rows(5)
                ->disabled(),
            WordTextArea::make('childs_quality_and_area_of_growth')
                ->label("Explain your child's most endearing quality and an area of growth.")
                ->helperText("Please limit your answer to 75 words.")
                ->lazy()
                ->required()
                ->rows(5)
                ->disabled(),
            WordTextArea::make('something_about_child')
                ->label("Please tell us something about your child that does not appear on this application.")
                ->helperText("Please limit your answer to 75 words.")
                ->lazy()
                ->required()
                ->rows(5)
                ->disabled(),
            TextInput::make('parent_statement_by')
                ->label('Parent/Guardian Statement Submitted By')
                ->required()
                ->disabled(),
            Select::make('parent_relationship_to_student')
                ->label('Relationship to Applicant')
                ->options(ParentType::asSameArray())
                ->preload()
                ->required()
                ->disabled(),
        ];
    }

    public function getStudentStatement()
    {
        return [
            Placeholder::make('section_student_statement')
                ->label('')
                ->content(new HtmlString('* This section is to be completed by the applicant only.')),
            WordTextArea::make('why_did_you_apply')
                ->label("Why do you want to attend St. Ignatius College Preparatory?")
                ->helperText("Please limit your answer to 75 words.")
                ->lazy()
                ->required()
                ->rows(5)
                ->disabled(),
            WordTextArea::make('greatest_challenge')
                ->label("What do you think will be your greatest challenge at SI and how do you plan to meet that challenge?")
                ->helperText("Please limit your answer to 75 words.")
                ->lazy()
                ->required()
                ->rows(5)
                ->disabled(),
            WordTextArea::make('religious_activity_participation')
                ->label("What religious activity or activities do you plan on participating in at SI as part of your spiritual growth and why?")
                ->helperText("Please limit your answer to 75 words.")
                ->lazy()
                ->required()
                ->rows(5)
                ->disabled(),
            WordTextArea::make('favorite_and_difficult_subjects')
                ->label("What is your favorite subject in school and why? What subject do you find the most difficult and why?")
                ->helperText("Please limit your answer to 75 words.")
                ->lazy()
                ->required()
                ->rows(5)
                ->disabled(),
            
        ];
    }

    public function getActivityForm()
    {
        return [
            Placeholder::make('section_school_activity')
                ->label('')
                ->content(new HtmlString('
                <p>* This section is to be completed by the applicant only.</p>
                <p>List up to four current extracurricular activities that you are most passionate about.</p>
            ')),
            Repeater::make('activities')
                ->label('')
                ->disableItemCreation()
                ->disableItemDeletion()
                ->disableItemMovement()
                ->schema([
                    TextInput::make('activity_name')
                        ->label('Activity Name')
                        ->lazy()
                        ->required()
                        ->disabled(),
                    Select::make('number_of_years')
                        ->label('Number of Years')
                        ->options($this->getYearsOptions())
                        ->preload()
                        ->required()
                        ->disabled(),
                    Select::make('hours_per_week')
                        ->label('Hours per Week')
                        ->options($this->getHoursPerWeekOptions())
                        ->preload()
                        ->lazy()
                        ->required()
                        ->disabled(),
                    WordTextArea::make('activity_information')
                        ->label("Explain your involvement in this activity.  For example, the team(s) you play on, position(s) you play, concert(s)/recital(s) you have performed in, and/or why you are involved in this activity.")
                        ->helperText("Please limit your answer to 75 words.")
                        ->lazy()
                        ->required()
                        ->rows(5)
                        ->disabled(),
                    
                    ]),
            Grid::make(1)
                ->schema([
                    WordTextArea::make('most_passionate_activity')
                        ->label("From the activities listed above, select the activity you are most passionate about continuing at SI and describe how you would contribute to this activity.")
                        ->lazy()
                        ->required()
                        ->rows(5)
                        ->disabled(),
                    WordTextArea::make('new_extracurricular_activities')
                        ->label("Select two new extracurricular activities that you would like to be involved in at SI.  Explain why these activities appeal to you.")
                        ->helperText("Please limit your answer to 75 words.")
                        ->lazy()
                        ->required()
                        ->rows(5)
                        ->disabled(),
                ])
            
        ];
    }

    public function getWritingSampleForm()
    {
        return [
            Placeholder::make('section_writing_sample')
                ->label('')
                ->content(new HtmlString('* This section is to be completed by a the applicant only. Select one of the topics below.  Write a complete essay with a maximum of 250 words.')),
            WordTextArea::make('writing_sample_essay')
                ->label(new HtmlString('<div class="font-medium text-gray-700">

                        <section class="space-y-4">
                            <h3 class="font-bold font-heading text-primary-red">What matters to you? How does that motivate you, impact your life, your outlook, and/or your identity?</h3>
                            <p class="font-medium">What matters to you might be an activity, an idea, a goal, a place, and/or a thing.</p>
                            <p class="font-medium"> <strong>PLEASE NOTE:</strong> This essay should be about you and your thoughts. It should not be about the life of another person you admire.</p>
                        </section>
                        <section class="mt-8">
                            <h3 class="text-xl font-bold text-center text-gray-900 font-heading">--OR--</h3>
                        </section>
                        
                        <section class="mt-8 space-y-4">
                            <h3 class="relative font-heading text-primary-red">
                                <span class="absolute font-medium" style="left: -10px">*</span>
                                <span class="font-bold">What is an obstacle you have overcome?</span>
                            </h3>
                            <p>
                                Explain how the obstacle impacted you and how you handled the situation (i.e., positive and/or negative attempts along the way or maybe how you are still working on it).
                            </p>
                            <p>
                                Include what you have learned from the experience and how you have applied (or might apply) this to another situation in your life.
                            </p>
                        </section>
                    </div>'))
                ->helperText('Please limit your answer to 250 words.')
                ->rows(15)
                ->lazy()
                ->required()
                ->disabled(),
            Checkbox::make('writing_sample_essay_acknowledgement')
                ->columnSpan('full')
                ->validationAttribute('checkbox')
                ->label('By clicking this box, I (applicant) declare that to
                the best of my knowledge, the information provided in the application submitted to
                St. Ignatius College Preparatory on this online application is true and complete.
                ')
                ->extraAttributes(['class' => 'opacity-100'])
                ->required()
                ->disabled(),
            TextInput::make('writing_sample_essay_by')
                ->label('Submitted By')
                ->required()
                ->disabled()
        ];
    }

    public function getPlacementForm()
    {
        return [
            ReadonlyRadio::make('has_learning_disability')
                ->label('Would you like to upload any learning difference documentation?')
                ->options([
                    0 => 'No',
                    1 => 'Yes'
                ])
                ->required()
                ->disabled(),
            FileUpload::make('file_learning_documentation')
                ->label('Upload your file here. You can attach multiple files.')
                ->multiple()
                ->maxSize(25000)
                ->reactive()
                ->enableOpen()
                ->enableDownload()
                ->directory("learning_docs/" . date('Ymdhis') . '/' . $this->app->id)
                ->visible(fn(Closure $get)  =>  $get('has_learning_disability') == 1  )
                ->preserveFilenames()
                ->afterStateHydrated(function(Closure $get, Closure $set, $state){
                    if($state){
                        $date = Carbon::parse($get('placement_test_date'))->addDays(7)->format('Y-m-d');
                        $set('placement_test_date', $date);
                    }
                })
                ->disabled(),
            Grid::make(1)
                ->schema([
                    ReadonlyRadio::make('entrance_exam_reservation')
                        ->label("Indicate the date and the high school where your child will take the entrance exam. If you submit your application after the November 15th (by midnight) deadline, we may not be able to accommodate you for the HSPT at SI on December 2nd.")
                        ->options(function(Closure $get){

                            $array = [];
                            $array[] = "At SI on " . date('F j, Y', strtotime( $get('placement_test_date') ));

                            if($get('has_learning_disability')){
                                $array[] =  "At SI on December 9, 2023 (this date is only for those who qualify for Extended Time)";
                            }
                            
                            $array[] = "At Other Catholic High School";


                            return array_combine($array, $array);
                        })
                        ->required()
                        ->disabled(),
                    Grid::make(1)
                        ->visible(fn(Closure $get) => $get('entrance_exam_reservation') === 'other')
                        ->schema([
                            TextInput::make('other_catholic_school_name')
                                ->label("Other Catholic School Name")
                                ->required()
                                ->disabled(),
                            TextInput::make('other_catholic_school_location')
                                ->label("Other Catholic School Location")
                                ->required()
                                ->disabled(),
                            DatePicker::make('other_catholic_school_date')
                                ->label("Other Catholic School Date")
                                ->required()
                                ->lazy()
                                ->disabled(),
                        ])
                ])
            
        ];
    }

    public function getFinalStepsForm()
    {
        return [
            Placeholder::make('Documentation')
                ->label('')
                ->content(new HtmlString('
                <div>
                    <section>
                        <h4 class="text-xl font-bold text-center font-heading text-primary-blue">Release Authorization</h4>
                        <p class="mt-8 text-sm">
                            I hereby consent to the release of my child`s academic records and test scores to St. Ignatius College Preparatory for the purpose of evaluating his or her application for admission. In authorizing this release, I acknowledge that I waive my right to read the Confidential School/Clergy Recommendations and my child`s application file. I further consent to St. Ignatius College Preparatory issuing academic progress reports to my child`s current school listed on this application during my child`s four years at St. Ignatius College Preparatory.
                        </p>
                    </section>
                </div>
            ')),
            Checkbox::make('agree_to_release_record')
                ->columnSpan('full')
                ->label('By checking this box, you acknowledge that you have read, understand and agree to the above.')
                ->lazy()
                ->required()
                ->disabled(),
            Checkbox::make('agree_academic_record_is_true')
                ->columnSpan('full')
                ->label('By checking the box, I (parent(s)/guardian(s))
                declare that to the best of my knowledge, the information provided in the
                application submitted to St. Ignatius College Preparatory on this online application
                is true and complete.')
                ->lazy()
                ->required()
                ->disabled(),
        ];
    }

    public function getYearsOptions()
    {
        $arr = range(1, 9);
        $arrs = array_combine($arr, $arr);

        return $arrs + ['10+' => '10+'];
    }

    public function getHoursPerWeekOptions()
    {
        $arr = ['1 - 2', '3 - 5', '6 - 10', '11 - 15', '16+'];
        $arrs = array_combine($arr, $arr);
        return $arrs;
    }
}
