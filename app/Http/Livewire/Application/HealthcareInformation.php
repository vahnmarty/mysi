<?php

namespace App\Http\Livewire\Application;

use Auth;
use Closure;
use App\Models\User;
use Livewire\Component;
use App\Enums\CrudAction;
use App\Models\Healthcare;
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

class HealthcareInformation extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $data = [];

    public $enable_form = false;

    public $action = CrudAction::Create; 
    public $model_id;
    
    public function render()
    {
        return view('livewire.application.healthcare-information');
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
        return Healthcare::where('account_id', accountId());
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('insurance_company'),
            TextColumn::make('policy_number'),
            TextColumn::make('first_name')
                ->formatStateUsing(function(Healthcare $record){
                    return $record->first_name . ' ' . $record->last_name;
                })
                ->label("Doctor's Name"),
            TextColumn::make('phone')
                ->label("Doctor's Phone"),
        ];
    }

    protected function getTableActions(): array
    {
        return [ 
            Action::make('edit')
                ->action(function(Healthcare $record){
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
        return 'No Healthcare Data yet';
    }
 
    protected function getTableEmptyStateDescription(): ?string
    {
        return 'You may create a healthcare information using the form below.';
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
                TextInput::make('insurance_company')
                    ->label('Medical Insurance Company')
                    ->required(),
                TextInput::make('policy_number')
                    ->label('Medical Policy Number')
                    ->required(),
                TextInput::make('first_name')
                    ->label("Physician's First Name")
                    ->required(),
                TextInput::make('last_name')
                    ->label("Physician's Last Name")
                    ->required(),
                TextInput::make('phone')
                    ->label("Physician's Phone Number")
                    ->tel()
                    ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000'))
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                    ->required(),
                TextInput::make('phone_extension')
                    ->label("Physician's Extension")
            ])
            
        ];
    }

    public function save()
    {
        $data = $this->form->getState();

        if($this->action == CrudAction::Create){
            Healthcare::create($data);

            Notification::make()
                ->title('Parent created successfully')
                ->success()
                ->send();

            $this->reset('data');

        }
        else{
            $model = Healthcare::find($this->model_id);
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
