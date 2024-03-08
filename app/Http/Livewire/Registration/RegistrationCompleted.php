<?php

namespace App\Http\Livewire\Registration;

use Closure;
use Livewire\Component;
use App\Models\Registration;
use App\Models\ContactDirectory;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Livewire\TemporaryUploadedFile;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;

class RegistrationCompleted extends Component implements HasForms
{
    use InteractsWithForms;
    
    public Registration $registration;

    public $directory = [];

    public $data = [];

    public function render()
    {
        return view('livewire.registration.registration-completed');
    }

    public function mount($uuid)
    {
        $this->registration = Registration::with('student')->whereUuid($uuid)->firstOrFail();

        $this->directory = ContactDirectory::orderBy('sort')->get()->toArray();

        
        $this->form->fill($this->registration->toArray());
    }

    protected function getFormSchema()
    {
        $data = $this->registration;
        
        return [
            Section::make('Your SI Accounts')
                ->collapsible()
                ->collapsed(true)
                ->schema($this->getSiAccountSection($data)),
            Section::make('Wildcat Welcome Portal')
                ->collapsible()
                ->collapsed(true)
                ->schema($this->getWildcatPortal($data)),
            Section::make('SI Big Cat/Little Cat Program')
                ->collapsible()
                ->collapsed(true)
                ->schema($this->getCatProgram($data)),
            Section::make('Required Tasks')
                ->collapsible()
                ->collapsed(true)
                ->schema($this->getRequiredTasks($data)),
            Section::make('Additional Options')
                ->collapsible()
                ->collapsed(true)
                ->schema($this->getAdditionalOptions($data)),
            Section::make('SI Contacts')
                ->collapsible()
                ->collapsed(true)
                ->schema($this->getContacts($this->directory)),
        ];
    }

    protected function getStatePath()
    {
        return 'data';
    }

    protected function getSiAccountSection($data): array
    {
        return [
            Placeholder::make('your_si_account')
                ->label('')
                ->content(new HtmlString('<div>
                    <p>
                        Login today to your new <a  href="https://mail.google.com" target="_blank" class="text-link"><u>SI Google Account</u></a> and access your email using the credentials below. Make sure to use <b>@siprep.org</b>, not @gmail.com.
                    </p>
                </div>')),
            Placeholder::make('student.full_name')
                ->label('')
                ->content(new HtmlString('<h3 class="font-bold text-primary-red">'.$data['student']['full_name'].'</h3>')),
            Grid::make(2)
                ->schema([
                    TextInput::make('student.si_email')
                        ->label('')
                        ->disabled(),
                    TextInput::make('student.si_email_password')
                        ->label('')
                        ->disabled(),
                    Hidden::make('student.si_email_username')
                        ->label('')
                        ->disabled()
                        ->reactive()
                        ->formatStateUsing(function(Closure $get){
                            $parts =  explode('@', $get('student.si_email'));

                            return $parts[0];
                        }),
                ]),
            Placeholder::make('desc2')
                ->label('')
                ->content(fn(Closure $get) =>new HtmlString('<div class="mt-4">
                    <p>
                        You will receive important information via your SI email account throughout the summer, including messages about upcoming activities and your first assignment in June. SI students are expected to check their email inbox daily.
                    </p>
                    <p class="mt-4">
                        If you would like to change your password, you must change it via the SI website by going to <a class="text-link" href="https://www.siprep.org/pw" target="_blank"><u>https://www.siprep.org/pw</u></a>.
                        Do not use the “Forgot my password” link in the Google login box.
                    </p>
                    <p class="mt-4">
                        If you have any difficulties with your email account, please contact <a class="text-link" href="mailto:SI_librarians@siprep.org?subject=Problems with Email Account" target="_blank"><u>SI_librarians@siprep.org</u></a>.
                    </p>

                    <div class="mt-6">
                        <h4 class="font-bold">Difference Between Your Email and Username</h4>
                        <p class="mt-3">
                            Please note that there is a difference between your SI email address and your SI
                            username. When logging into Gmail, you will use your SI email address ('.$get('student.si_email').'). When logging into other SI systems, such as PowerSchool and Canvas, you will
                            use your normal SI username ('.$get('student.si_email_username').'). The password will be the same for both your username and email and password changes will flow to both types of accounts.  This is why it\'s very important to only do
                            password changes on our website at <a href="https://www.siprep.org/pw" target="_blank" class="text-link">https://www.siprep.org/pw</a> and not on any other site.
                        </p>
                    </div>

                    
            </div>'))
        ];
    }

    protected function getWildcatPortal($data): array
    {
        return [
            Placeholder::make('wildcat_portal')
                ->label('')
                ->content(new HtmlString('<div>
                    We will be in touch throughout the summer.&nbsp;&nbsp;Look for our Wildcats Welcome Newsletter every other Thursday in your inbox.&nbsp;&nbsp;Stay informed all summer by visiting our <a style="color: #0086e7; cursor: pointer;" href="http://www.siprep.org/welcome" target="_blank"><u>Wildcat Welcome Portal</u></a>.&nbsp;&nbsp;
                        
                    We will be updating this site throughout the summer.&nbsp;&nbsp;Answers to any questions that may arise over the summer can usually be found on the Welcome Portal.
                </div>')),
        ];
    }

    protected function getCatProgram($data): array
    {
        return [
            Placeholder::make('cat_program')
                ->label('')
                ->content(new HtmlString('<div class="space-y-3">
                <p>Hello Incoming Wildcats! </p>
                <p>
                    Welcome to the St. Ignatius family!  Our primary goal is to create a comfortable transition into the SI community, specifically through our Big Cat Program.  This is a Big Brother/Big Sister dynamic that works to encourage and include all individuals.  We hope that our Big Cat/Little Cat program will foster friendships between our freshmen and senior classes.  These relationships will help you on your journey through the start of Frosh year and ease any first-year jitters we all experience.  We plan to host fun and interactive activities that will get you more familiar with our campus, your class of '. app_variable('class_year').', and our overall school environment.
                </p>
                <p>
                    The answers you provided in the SI Co-Curriculars section of the Registration form will help us pair you with a Big Cat that has similar interests as you.
                </p>
                <p>
                    We\'re so excited to meet you!  Go Cats!
                </p>
            </div>')),
        ];
    }

    protected function getRequiredTasks($data): array
    {
        return [
            Card::make()
                ->columns(1)
                ->schema([
                    Placeholder::make('tech_training')
                        ->label('')
                        ->content(new HtmlString('<h3 class="text-xl font-bold text-primary-red">Tech Training</h3>')),
                    Placeholder::make('librarian_message')
                        ->label('')
                        ->content(fn(Closure $get) => new HtmlString('<div class="space-y-3">
                        <p>Dear member of the class of 2028.</p>
                        <p>We are excited to meet you. Our names are Ms. Brancoli and Ms. Wenger, and we are the librarians here at SI.</p>
                        <p>First, we need you to complete <a href="https://docs.google.com/forms/d/e/1FAIpQLScfuBbTGHqfOVvDYPnCvCuBiH8FPwRzjhVgbc4rOb_hrN5qfw/viewform?usp=sf_link" target="_blank" class="text-link">this form</a> by May 17th.</p>
                        <p>Next, we want to tell you about the mandatory tech training that you will complete. You will receive information about this tech training, including the course invitation, in your SI email.</p>
                        <p>This online training will take approximately four hours to complete, and you will need to complete it between August 1st and August 10th. Don’t worry. We will remind you, but you will need to check your email regularly for information.</p>
                        <p>What do librarians at SI do? To start, we answer questions.</p>
                        <div>
                            <p>You might have one of these questions this summer:</p>
                            <ol class="pl-8 list-decimal">
                                <li>I lost my email password and I can’t log on. What do I do?</li>
                                <li>How do I get my textbooks?</li>
                                <li>Who do I contact about __ (a sport, a club, etc)?</li>
                                <li>Any other school related questions.</li>
                            </ol>
                        </div>
                        <p>If you find yourself with any of the above, please email us at <a class="text-link"  href="mailto:si_librarians@siprep.org">si_librarians@siprep.org</a>. We are here to help.</p>
                        <p>We can’t wait to start learning with you,</p>
                        <p>Ms. Brancoli and Ms. Wenger</p>
                    </div>'))
                ]),
            Card::make()
                ->columns(1)
                ->schema([
                    Placeholder::make('dean_title')
                        ->label('')
                        ->content(fn(Closure $get) => new HtmlString('<h3 class="text-xl font-bold text-primary-red">
                            SI Deans’ Office Required Form '. ( !empty($get('student.health_form_file')) ? '(Completed)' : '') .'
                        </h3>')),
                    Placeholder::make('required_form')
                        ->label('')
                        ->content(fn(Closure $get) => new HtmlString('<div class="space-y-3">
                        <p class="text-sm font-bold text-gray-500">
                            Upload SFUSD Freshman Health Form: Due by '. app_variable('health_form_due_date', 'display_value').'
                        </p>
                        <p>
                            Please download: <a class="text-link" href="'.asset('files/SIFreshmanHealthForm.pdf').'" target = "_blank"><u>SFUSD Freshman Health Form</u></a>. Note that this form requires a doctor\'s signature.
                        </p>
                    </div>')),
                    FileUpload::make('student.health_form_file')
                        ->label(new HtmlString('<p>Upload your SFUSD Freshman Health Form here.  Please use the following naming convention for the file:</p>
                        <strong class="text-danger">{Student_First_Name}_{Student_Last_Name}_{Name_of_File}.pdf</strong></p>'))
                        ->helperText(new HtmlString("(The file doesn’t have to be a PDF.)<p class='mt-4'><strong>NOTE:</strong> Do NOT upload the Ticket to Play Medical Clearance Form here.</p>"))
                        ->maxSize(25000)
                        ->reactive()
                        ->enableOpen()
                        ->enableDownload()
                        ->directory("health_form_files")
                        ->multiple()
                        //->preserveFilenames()
                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                            return (string) $this->student->id . '_' . $this->student->first_name .'_' .$this->student->last_name  .'/' . clean_string($file->getClientOriginalName());
                        })
                        ->afterStateHydrated(function(Closure $get, Closure $set, $state){
                        })
                        ->afterStateUpdated(function(Component $livewire, FileUpload $component, Closure $get, Closure $set, $state){
                            $component->saveUploadedFiles();
                            $files = \Arr::flatten($component->getState());
                            $this->saveFile($files);
                        })
                        ->required(),
                ]),
            Card::make()
                ->columns(1)
                ->schema([
                    Placeholder::make('ipad_policy')
                        ->label('')
                        ->content(new HtmlString('<h3 class="text-xl font-bold text-primary-red">
                        SI\'s iPad Policy
                        </h3>')),
                    Placeholder::make('ipad_policy_verbiage')
                        ->label('')
                        ->content(new HtmlString('<p>
                        All students are required to have a fully charged iPad with them at school each day. Students must have the iPads they will use for school by July 29th. For more information, please check out our Technology page <a href="https://www.siprep.org/academics/technology" target="_blank" class="text-link">here</a>.
                        </p>')),
                ]),
        ];
    }

    public function saveFile($files)
    {
        $student = $this->registration->student;
        $student->health_form_file = $files;
        $student->save();
    }

    protected function getAdditionalOptions($data): array
    {
        return [
            Card::make()
                ->columns(1)
                ->schema([
                    Placeholder::make('si_summer_programs')
                        ->label('')
                        ->content(new HtmlString('<h3 class="text-xl font-bold text-primary-red">SI Summer Programs </h3>')),
                    Placeholder::make('cat_camp')
                        ->label('')
                        ->content(new HtmlString('<div>
                        <p>
                            If you are interested in exclusive rising 9th grade programs this summer before the school year begins, please click on these two SI Summer programs links:
                        </p>
                        <div class="mt-4 space-y-4">
                            <div>
                                <p class="font-bold">' . app_variable('cat_camp_title'). '</p>
                                <a class="text-link" href="' .app_variable('cat_camp_url').' " target = "_blank">'.app_variable('cat_camp_url').'</a>
                            </div>
                            <div>
                                <p class="font-bold">' . app_variable('rising_9th_grade_title').'</p>
                                <a class="text-link" href="' .app_variable('rising_9th_grade_url').'" target = "_blank">
                                    ' .app_variable('rising_9th_grade_url').'
                                </a>
                            </div>
                        </div>
                    </div>'))
                ]),
            Card::make()
                ->columns(1)
                ->schema([
                    Placeholder::make('clearance_form_title')
                        ->label('')
                        ->content(new HtmlString('<h3 class="text-xl font-bold text-primary-red">SI Athletics Ticket to Play Medical Clearance Form</h3>')),
                    Placeholder::make('si_athletics')
                        ->label('')
                        ->content(new HtmlString('<div>
                        <p class="mt-1 text-sm font-bold text-gray-500">
                            Due: ' . app_variable('family_id_start_date', 'display_value') . ' – ' . app_variable('family_id_end_date', 'display_value') . '
                        </p>
                        <div class="mt-3 text-sm">
                            To participate in SI Athletics, including tryouts, you must register to play through SI’s <a 
                            class="text-link" href="' . app_variable('family_id_url') . '" target="blank"><u>FamilyID system</u></a>.&nbsp;&nbsp;As a part of this process, you will be required to upload SI’s Ticket 
                            to Play Medical Clearance Form.&nbsp;&nbsp;The ticket to play requires a physical exam with a doctor.&nbsp;&nbsp;We strongly encourage you to schedule this exam between ' . app_variable('family_id_start_date', 'display_value') . '  and ' . app_variable('family_id_end_date', 'display_value') . ' to maintain athletic 
                            eligibility for the entire school year.&nbsp;&nbsp;The FamilyID website will open for registration on ' . app_variable('family_id_start_date', 'display_value') . '.&nbsp;&nbsp;Please do not register until you are ready to upload your ticket to play.
                        </div>
                        <p class="mt-3 text-sm">
                            Download SI’s Ticket to Play Medical Clearance Form <a class="text-link" href="https://resources.finalsite.net/images/v1674767044/siprep/t6goeoxvhp5mj2nzsgcu/MedicalClearanceFormTemplate.pdf" target="_blank"><u>here</u></a>.
                        </p>
                    </div>'))
                ]),
            Card::make()
                ->columns(1)
                ->schema([
                    Placeholder::make('prep_shop_title')
                        ->label('')
                        ->content(new HtmlString('<h3 class="text-xl font-bold text-primary-red">Visit the SI Prep Shop</h3>')),
                    Placeholder::make('prep_shops')
                        ->label('')
                        ->content(new HtmlString('<div>
                        <p>
                        If you would like to purchase SI-branded merchandise, visit The Prep Shop at <a  class="text-link" href="https://siprepshop.com." target="_blank">https://siprepshop.com.</a>
                        </p>
                    </div>'))
                ]),

        ];

    }

    protected function getContacts($directories): array
    {
        $directoryForm = [];

        foreach($directories as $dir)
        {
            $directoryForm[] = Placeholder::make('dir' . $dir['id'])
                ->label('')
                ->content(new HtmlString('<div class="p-3 bg-gray-100 border rounded-md">
                <h4 class="font-bold">'.$dir['name'].'</h4>
                <p>'. $dir['representative_name'].'</p>
                <a href="mailto:'.$dir['representative_email'].'" class="text-sm text-link">'.$dir['representative_email'].'</a>
            </div>'));
        }

        return [
            Placeholder::make('wildcat_portal')
                ->label('')
                ->content(new HtmlString('<div>
                    For questions about a specific topic, please feel free to email the individuals below.
                </div>')),
            Grid::make(4)
                ->schema($directoryForm)
        ];
    }
}
