<?php

namespace App\Http\Livewire\Application;

use Auth;
use App\Models\User;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\Child;
use Livewire\Component;
use App\Enums\CrudAction;
use App\Enums\GradeLevel;
use App\Enums\ParentType;
use App\Enums\RacialType;
use App\Enums\Salutation;
use App\Enums\ConditionBoolean;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class ChildrenInformation extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $data = [];

    public $enable_form = false;

    public $action = CrudAction::Create; 
    public $model_id;
    
    public function render()
    {
        return view('livewire.application.children-information');
    }

    public function mount()
    {
        $this->form->fill([
            'account_id' => accountId()
        ]);

        if($this->getTableQuery()->count() <= 0){
            $this->enable_form = true;
        }
    }

    public function getTableQuery()
    {
        return Child::where('account_id', accountId());
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('relationship'),
            TextColumn::make('name')->formatStateUsing(fn(Child $record) => $record->getFullName() ),
            TextColumn::make('mobile_phone'),
            TextColumn::make('email'),
        ];
    }

    protected function getTableActions(): array
    {
        return [ 
            Action::make('edit')
                ->action(function(Child $record){
                    $this->model_id = $record->id;
                    $this->action = CrudAction::Update;
                    $this->enable_form = true;
                    $this->form->fill($record->toArray());
                    
                }),
            DeleteAction::make()->icon(''),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [ 
            CreateAction::make()
                ->label('Add')
                ->action(function(){
                    $this->reset('model_id');
                    $this->action = CrudAction::Create;
                    $this->enable_form = true;
                    $this->form->fill();
                })
        ];
    }


    protected function isTablePaginationEnabled(): bool 
    {
        return false;
    }

    protected function getTableEmptyStateIcon(): ?string 
    {
        return 'heroicon-o-user';
    }
 
    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Children Data yet';
    }
 
    protected function getTableEmptyStateDescription(): ?string
    {
        return 'You may create a child information using the form below.';
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(3)
            ->schema([
                Hidden::make('account_id'),
                TextInput::make('first_name')->label('Legal First Name')->required(),
                TextInput::make('middle_name')->label('Legal Middle Name')->required(),
                TextInput::make('last_name')->label('Legal Last Name')->required(),
                Select::make('suffix')->options(Suffix::asSelectArray())->required(),
                TextInput::make('preferred_first_name')->label('Preferred First Name')->helperText('(must be different from First Name)')->required(),
                DatePicker::make('birthdate')->label('Date of Birth')->required(),
                Select::make('gender')->options(Gender::asSelectArray())->required(),
                TextInput::make('email')->label('Preferred Email')->email()->required(),
                TextInput::make('mobile_phone')
                    ->required(),
                    //->tel()
                    //->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                Select::make('race')
                    ->label('How do you identify racially?')
                    ->multiple()->options(RacialType::asSameArray())
                    ->required()
                    ->columnSpan(2),
                Radio::make('multi_racial_flag')
                    ->label('Multi Racial?')
                    ->options(ConditionBoolean::asSelectArray())->required(),
                TextInput::make('ethnicity')->label('What is your ethnicity?'),

                Select::make('current_grade')->options(GradeLevel::asSelectArray())->required(),


                TextInput::make('alt_email')->label('Alternate Email')->email(),
                TextInput::make('employer')->required(),
                TextInput::make('job_title')->required(),
            ])
            
        ];
    }

    public function save()
    {
        $data = $this->form->getState();

        if($this->action == CrudAction::Create){
            Child::create($data);

            Notification::make()
                ->title('Parent created successfully')
                ->success()
                ->send();

            $this->reset('data');

        }
        else{
            $model = Child::find($this->model_id);
            $model->update($data);

            Notification::make()
                ->title('Parent updated successfully')
                ->success()
                ->send();

            $this->reset('data');

        }

        $this->enable_form = false;
        
    }

}