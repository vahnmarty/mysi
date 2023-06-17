<?php

namespace App\Http\Livewire\Application;

use Livewire\Component;
use App\Models\Application;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Http\Livewire\Application\Forms\LegacyFormTrait;
use App\Http\Livewire\Application\Forms\ParentFormTrait;
use App\Http\Livewire\Application\Forms\AddressFormTrait;
use App\Http\Livewire\Application\Forms\SiblingFormTrait;
use App\Http\Livewire\Application\Forms\StudentFormTrait;
use App\Http\Livewire\Application\Forms\FamilyMatrixTrait;

class ApplicationForm extends Component implements HasForms
{
    use InteractsWithForms;

    # Import Traits
    use StudentFormTrait, AddressFormTrait, ParentFormTrait, SiblingFormTrait, FamilyMatrixTrait, LegacyFormTrait;
    
    # Model
    public Application $app;

    # Constant Variables
    const ChildModel = 'child';
    const NotListed = 'Not Listed';

    # Form
    public $data = [];

    public function render()
    {
        return view('livewire.application.application-form');
    }

    public function mount($uuid)
    {
        $this->app = Application::whereUuid($uuid)->firstOrFail();
        $account = $this->app->account->load('addresses', 'parents', 'children', 'legacies');

        $data = $this->app->toArray();
        $data['student'] = $this->app->student->toArray();
        $data['addresses'] = $account->addresses->toArray();
        $data['parents'] = $account->parents->toArray();
        $data['siblings'] = $account->children()->where('id', '!=', $this->app->child_id)->get()->toArray();
        $data['matrix'] = $this->createMatrix($this->app, $data['parents'], $data['siblings']);
        $data['legacy'] = $account->legacies->toArray();
        $data['autosave'] = true;

        $this->form->fill($data);
    }

    public function createMatrix(Application $app, $parents, $siblings)
    {
        $matrix = [];

        foreach($parents as $parent)
        {
            $parentMatrix = $app->matrix()->firstOrCreate([
                'parent_id' => $parent['id']
            ]);

            $matrix[] = $parentMatrix->toArray();
        }

        foreach($siblings as $child)
        {
            $siblingMatrix = $app->matrix()->firstOrCreate([
                'child_id' => $child['id']
            ]);

            $matrix[] = $siblingMatrix->toArray();
        }

        return $matrix;
    }

    protected function getFormSchema(): array
    {
        return [
            Toggle::make('autosave'),
            Section::make('Student Information')
                ->collapsible()
                // ->description(new HtmlString('
                // <div class="flex items-start gap-8 mt-8">
                //     <strong>DIRECTIONS:</strong>
                //     <div>
                //         <p>
                //           This section is to be completed by a parent/guardian. Please add all necessary information for the student.  Required fields are in red.
                //         </p>
                //         <p>
                //         Please make sure every required field is completed before saving your work. To save your work, you must click the Next/Save button at the bottom of the page.
                //         </p>
                //     </div>
                // </div>'))
                ->schema($this->getStudentForm()),
            Section::make('Address Information')
                ->schema($this->getAddressForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('Parent/Guardian Information')
                ->schema($this->getParentForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('Sibling Information')
                ->schema($this->getSiblingForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('Family Matrix')
                ->description('If the matrix is not showing all the items, please refresh the page.')
                ->schema($this->getFamilyMatrix())
                ->collapsible()
                ->collapsed(true),
            Section::make('Legacy Information')
                ->schema($this->getLegacyForm())
                ->collapsible()
                ->collapsed(true),
            Section::make('Spiritual and Community Information')
                ->schema([

                ])
                ->collapsible()
                ->collapsed(true),
            Section::make('Parent/Guardian Statement')
                ->schema([

                ])
                ->collapsible()
                ->collapsed(true),
            Section::make('Student Statement')
                ->schema([

                ])
                ->collapsible()
                ->collapsed(true),
            Section::make('School Activities')
                ->schema([

                ])
                ->collapsible()
                ->collapsed(true),
            Section::make('Writing Sample')
                ->schema([

                ])
                ->collapsible()
                ->collapsed(true),
            Section::make('High School Placement Test')
                ->schema([

                ])
                ->collapsible()
                ->collapsed(true),
            Section::make('Final Steps')
                ->schema([

                ])
                ->collapsible()
                ->collapsed(true),
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

    public function autoSave($column, $value, $relationship = null)
    {
        if($relationship){
            $model = $this->app->{$relationship};
        }else{
            $model = $this->app;
        } 

        if(is_array($value)){
            $value = implode(',', $value);
        }

        $model->$column = $value;
        $model->save();
    }

}
