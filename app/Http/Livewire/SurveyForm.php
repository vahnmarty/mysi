<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use Livewire\Component;
use App\Models\Application;
use App\Models\NotificationMessage;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;

class SurveyForm extends Component implements HasForms
{
    use InteractsWithForms;
    
    public $type;

    public $data = [];
    
    public function render()
    {
        return view('livewire.survey-form')
            ->layoutData(['title' => 'Survey']);
    }

    public function mount($uuid)
    {
        $survey = Survey::whereUuid($uuid)->firstOrFail();

        $this->type = $survey->type;

        $this->form->fill();
    }

    protected function getFormStatePath(): string 
    {
        return 'data';
    }

    protected function getFormSchema()
    {
        return [
            TextInput::make('other_school')
                ->label('School that you plan to attend:')
                ->required(fn() => $this->type == 'Declined')
                ->visible(fn() => $this->type == 'Declined')
                ->inlineLabel(),
            Placeholder::make('school_desc')
                ->label('Please list, in order of preference, the schools to which you applied , the admission decision (accepted/waitlisted/not accepted), and Financial Aid or scholarship information, if applicable:'),
            TableRepeater::make('survey_schools')
                ->label('')
                ->defaultItems(4)
                ->hideLabels()
                ->createItemButtonLabel('Add')
                ->columnSpan('full')
                ->schema([
                    TextInput::make('school_name')
                        ->label('Name of School')
                        ->required(),
                    Select::make('school_decision')
                        ->label("School's Decision")
                        ->placeholder('Select')
                        ->options([])
                        ->required(),
                    Select::make('applied_for_aid')
                        ->label("Applied for Aid")
                        ->placeholder('Select')
                        ->options([])
                        ->required(),
                    TextInput::make('amount_aid')
                        ->label("Amount of Aid or Scholarship Offered")
                        ->prefix('$'),
                    TextInput::make('comment')
                        ->label("Comment")
                ]),
            Fieldset::make('Rank the three(3) most important reasons for choosing SI:')   
                ->columns(2)
                ->schema([
                    Select::make('most_important_reason')
                        ->label('Most Important Reason')
                        ->inlineLabel()
                        ->options([]),
                    TextInput::make('most_important_reason_comment')
                        ->label('Comment')
                        ->inlineLabel(),
                    Select::make('second_important_reason')
                        ->label('Second Important Reason')
                        ->inlineLabel()
                        ->options([]),
                    TextInput::make('second_important_reason_comment')
                        ->label('Comment')
                        ->inlineLabel(),
                    Select::make('third_important_reason')
                        ->label('Third Important Reason')
                        ->inlineLabel()
                        ->options([]),
                    TextInput::make('third_important_reason_comment')
                        ->label('Comment')
                        ->inlineLabel(),
                ]),
            Textarea::make('student_visit_program_remarks')
                ->label('If you attended a Student Visit Program or any other Admissions event, we would appreciate any comments regarding your experience:'),
            Textarea::make('si_admission_process')
                ->label('Please let us know your thoughts regarding the SI admissions process this year:')
        ];
    }

    public function submit()
    {
        $data = $this->form->getState();

        dd($data);
    }
}
