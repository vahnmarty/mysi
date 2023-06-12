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
use App\Http\Livewire\Application\Forms\ChildrenFormTrait;

class ApplicationForm extends Component implements HasForms
{
    use InteractsWithForms;

    # Trait Declaration
    use ChildrenFormTrait;
    
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
        $this->app = Application::with('child')->whereUuid($uuid)->firstOrFail();

        $this->form->fill($this->app->toArray());

        $this->data['autosave'] = true;
    }

    protected function getFormSchema(): array
    {
        return [
            Toggle::make('autosave')->disabled(),
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
                ->schema($this->getChildrenForm()),
            Section::make('Address Information')
                ->schema([

                ])
                ->collapsible()
                ->collapsed(true),
            Section::make('Parent/Guardian Information')
                ->schema([

                ])
                ->collapsible()
                ->collapsed(true),
            Section::make('Sibling Information')
                ->schema([

                ])
                ->collapsible()
                ->collapsed(true),
            Section::make('Family Matrix')
                ->schema([

                ])
                ->collapsible()
                ->collapsed(true),
            Section::make('Legacy Information')
                ->schema([

                ])
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
