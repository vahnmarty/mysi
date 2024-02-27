<?php

namespace App\Http\Livewire\Registration;

use Livewire\Component;
use App\Models\Registration;
use Illuminate\Support\HtmlString;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;

class RegistrationCompleted extends Component implements HasForms
{
    use InteractsWithForms;
    
    public Registration $registration;

    public $departments = [];

    public function render()
    {
        return view('livewire.registration.registration-completed');
    }

    public function mount($uuid)
    {
        $this->registration = Registration::whereUuid($uuid)->firstOrFail();

        $this->departments = $this->getDepartments();

 
    }
    

    public function getDepartments()
    {
        
        return [
            [
                "department" => "Academics",
                "person" => "Danielle Devencenzi",
                "email" => "ddevencenzi@siprep.org"
            ],
            [
                "department" => "Athletics",
                "person" => "John Mulkerrins",
                "email" => "jmulkerrins@siprep.org"
            ],
            [
                "department" => "Book Purchases",
                "person" => "Mike Ugawa",
                "email" => "mugawa@siprep.org"
            ],
            [
                "department" => "Financial Assistance",
                "person" => "Sharon Aline Brannen",
                "email" => "sbrannen@siprep.org"
            ],
            [
                "department" => "iPads Educational Apps and Technical Specs",
                "person" => "Jamie Pruden",
                "email" => "jpruden@siprep.org"
            ],
            [
                "department" => "iPads for Financial Aid Recipients",
                "person" => "Sharon Aline Brannen",
                "email" => "sbrannen@siprep.org"
            ],
            [
                "department" => "Library",
                "person" => "Christina Wenger",
                "email" => "cwenger@siprep.org"
            ],
            [
                "department" => "Magis Program",
                "person" => "Maricel Hernandez",
                "email" => "mhernandez@siprep.org"
            ],
            [
                "department" => "Placement Exams",
                "person" => "Jeannie Quesada",
                "email" => "jquesada@siprep.org"
            ],
            [
                "department" => "SFUSD Health Form",
                "person" => "Katie Kohmann",
                "email" => "kkohmann@siprep.org"
            ],
            [
                "department" => "Student Affairs/Activities",
                "person" => "Jeff Glosser",
                "email" => "jglosser@siprep.org"
            ],
            [
                "department" => "Summer Programs",
                "person" => "Betsy Mora",
                "email" => "summerprograms@siprep.org"
            ],
            [
                "department" => "Summer School",
                "person" => "Bill Gotch",
                "email" => "bgotch@siprep.org"
            ],
            [
                "department" => "Technology Onboarding",
                "person" => "SI Librarians",
                "email" => "si_librarians@siprep.org"
            ],
            [
                "department" => "Ticket to Play Medical Form for Athletics",
                "person" => "Josh Pendleton",
                "email" => "jpendleton@siprep.org"
            ],
            [
                "department" => "Transportation/Tuition/CYO Bus",
                "person" => "Kathleen McKeon",
                "email" => "kmckeon@siprep.org"
            ],
            [
                "department" => "Registration Website Issues",
                "person" => "Ramil Ferro",
                "email" => "rferro@siprep.org"
            ],
        ];

    }
}
