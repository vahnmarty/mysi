<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use Livewire\Component;
use App\Models\Application;
use App\Enums\SurveyReasonType;
use App\Models\NotificationMessage;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use App\Enums\SurveySchoolDecisionType;
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

    public Survey $survey;

    public $completed;

    protected $messages = [
        'data.other_school' => 'Please enter a school name',
        'data.schools.*.school_name' => 'Please enter a school name',
        'data.schools.*.school_decision' => 'Please select an option',
        'data.schools.*.applied_for_aid' => 'Please select an option',
    ];
    
    public function render()
    {
        return view('livewire.survey-form')
            ->layoutData(['title' => 'Survey']);
    }

    public function mount($uuid)
    {
        $survey = Survey::with('schools')->whereUuid($uuid)->firstOrFail();

        $this->completed = $survey->submitted();

        $this->survey = $survey;

        $this->type = $survey->type;

        $this->form->fill();
    }

    protected function getFormModel(): Survey 
    {
        return $this->survey;
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
                ->label('Please list, in order of preference, the schools to which you applied, the admission decision (accepted/waitlisted/not accepted), and Financial Aid or scholarship information, if applicable:'),
            TableRepeater::make('schools')
                ->relationship()
                ->label('')
                ->defaultItems(1)
                ->minItems(1)
                ->maxItems(10)
                ->validationAttribute('schools')
                ->extraAttributes(['id' => 'table-survey-schools'])
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
                        ->options(SurveySchoolDecisionType::asSameArray())
                        ->required(),
                    Select::make('applied_for_aid')
                        ->label("Applied for Aid")
                        ->placeholder('Select')
                        ->options([
                            0 => 'No',
                            1 => 'Yes'
                        ])
                        ->required(),
                    TextInput::make('amount_aid')
                        ->label("Amount of Aid or Scholarship Offered")
                        ->numeric()
                        ->prefix('$'),
                    TextInput::make('comment')
                        ->label("Comment")
                        ->maxLength(100),
                ]),
            Fieldset::make('Rank the three most important reasons for '. ($this->type == 'Declined' ? 'not ' : '')  .'choosing SI:')   
                ->columns(2)
                ->schema([
                    Select::make('most_important_reason')
                        ->label('Most Important Reason')
                        ->reactive()
                        ->inlineLabel()
                        ->options(fn() => $this->getReasonArray('most_important_reason')),
                    TextInput::make('most_important_reason_comment')
                        ->label('Comment')
                        ->maxLength(100)
                        ->inlineLabel(),
                    Select::make('second_important_reason')
                        ->label('Second Important Reason')
                        ->reactive()
                        ->inlineLabel()
                        ->options(fn() => $this->getReasonArray('second_important_reason')),
                    TextInput::make('second_important_reason_comment')
                        ->label('Comment')
                        ->maxLength(100)
                        ->inlineLabel(),
                    Select::make('third_important_reason')
                        ->label('Third Important Reason')
                        ->reactive()
                        ->inlineLabel()
                        ->options(fn() => $this->getReasonArray('third_important_reason')),
                    TextInput::make('third_important_reason_comment')
                        ->label('Comment')
                        ->inlineLabel()
                        ->maxLength(100),
                ]),
            Textarea::make('experience')
                ->label('If you attended a Student Visit Program or any other Admissions event, we would appreciate any comments regarding your experience:')
                ->maxLength(500),
            Textarea::make('si_admission_process')
                ->label('Please let us know your thoughts regarding the SI admissions process this year:')
                ->maxLength(500),
        ];
    }

    public function submit()
    {
        $data = $this->form->getState();

        $this->survey->update($data);
        $this->survey->submitted_at = now();
        $this->survey->save();

        $notificationMessage = NotificationMessage::where('application_id', $this->survey->application_id)->first();

        return redirect()->route('notifications.show', ['uuid' => $notificationMessage->uuid]);
    }

    private function getReasonArray($column)
    {
        $options = SurveyReasonType::asSameArray();
        $fields = ['most_important_reason', 'second_important_reason', 'third_important_reason'];

        foreach($fields as $field)
        {
            if($column != $field)
            {
                if( !empty($this->data[$field]) ){
                    unset($options[$this->data[$field]]);
                }
            }
            
        }
        
        return $options;
    }
}
