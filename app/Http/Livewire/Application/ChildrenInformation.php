<?php

namespace App\Http\Livewire\Application;

use Auth;
use Closure;
use App\Models\User;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\Child;
use App\Models\School;
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
use Filament\Forms\Components\TextInput\Mask;
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
            TextColumn::make('student_name')->label('Student Name')->formatStateUsing(fn(Child $record) => $record->getFullName() ),
            TextColumn::make('mobile_phone')->label('Mobile Phone'),
            TextColumn::make('personal_email')->label('Email'),
            TextColumn::make('current_school')->label('Current School'),
            TextColumn::make('current_grade')->label('Current Grade'),
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

    protected function getTableActionsColumnLabel(): ?string
    {
        return 'Action';
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(2)
            ->schema([
                Hidden::make('account_id')
                ->afterStateHydrated(function(Hidden $component, Closure $set, Closure $get, $state){
                    if(!$state){
                        $set('account_id', accountId());
                    }
                }),
                TextInput::make('first_name')
                    ->label('Legal First Name')
                    ->required(),
                TextInput::make('middle_name')
                    ->label('Legal Middle Name')
                    ->required(),
                TextInput::make('last_name')
                    ->label('Legal Last Name')
                    ->required(),
                Select::make('suffix')
                    ->label('Suffix')
                    ->options(Suffix::asSelectArray()),
                TextInput::make('preferred_first_name')
                    ->label('Preferred First Name')
                    ->helperText('(must be different from First Name)')
                    ->required(),
                Select::make('gender')
                    ->options(Gender::asSelectArray())
                    ->required(),
                TextInput::make('personal_email')
                    ->label('Preferred Email')
                    ->email()
                    ->required(),
                TextInput::make('mobile_phone')
                    ->label('Mobile Phone')
                    ->required()
                    ->mask(fn (Mask $mask) => $mask->pattern('(000) 000-0000'))
                    ->placeholder('(000) 000-0000')
                    ->tel(),
                    //->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                Select::make('current_school')
                    ->label('Current School')
                    ->options(School::active()->get()->pluck('name', 'name')->toArray())
                    ->preload()
                    ->searchable()
                    ->required(),
                Select::make('current_grade')
                    ->label('Current Grade')
                    ->options(GradeLevel::asSameArray())
                    ->required(),
            ])
            
        ];
    }

    public function cancel()
    {
        $this->enable_form = false;
        $this->form->fill();
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
