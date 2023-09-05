<?php

namespace App\Http\Livewire\Application;

use Auth;
use Closure;
use App\Models\User;
use App\Models\Legacy;
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

class LegacyInformation extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $data = [];

    public $enable_form = false;

    public $action = CrudAction::Create; 
    public $model_id;
    
    public function render()
    {
        return view('livewire.application.legacy-information');
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
        return Legacy::where('account_id', accountId());
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('first_name')
                ->label('Legacy Name')
                ->formatStateUsing(function(Legacy $record){
                    return $record->first_name . ' ' . $record->last_name;
                }),
            TextColumn::make('relationship_type'),
            TextColumn::make('graduation_year'),
        ];
    }

    protected function getTableActions(): array
    {
        return [ 
            Action::make('edit')
                ->action(function(Legacy $record){
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
        return 'No Legacy Data yet';
    }
 
    protected function getTableEmptyStateDescription(): ?string
    {
        return 'You may create a legacy information using the form below.';
    }

    protected function getFormStatePath(): string
    {
        return 'data';
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
                TextInput::make('first_name')->label('Legal First Name')->required(),
                TextInput::make('last_name')->label('Legal Last Name')->required(),
                
                Select::make('relationship_type')
                    ->options(ParentType::asSameArray())
                    ->required(),
                TextInput::make('graduation_year')
                    ->label('Graduation Year')
                    ->numeric()
                    ->minLength(4)
                    ->maxLength(4)
                    ->maxValue(2027)
                    ->required(),
            ])
            
        ];
    }

    public function save()
    {
        $data = $this->form->getState();

        if($this->action == CrudAction::Create){
            Legacy::create($data);

            Notification::make()
                ->title('Parent created successfully')
                ->success()
                ->send();

            $this->reset('data');

        }
        else{
            $model = Legacy::find($this->model_id);
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
