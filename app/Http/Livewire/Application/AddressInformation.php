<?php

namespace App\Http\Livewire\Application;

use Auth;
use Closure;
use App\Models\User;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\School;
use App\Models\Address;
use Livewire\Component;
use App\Enums\CrudAction;
use App\Enums\GradeLevel;
use App\Enums\ParentType;
use App\Enums\RacialType;
use App\Enums\Salutation;
use App\Enums\AddressType;
use App\Rules\PhoneNumberRule;
use App\Enums\ConditionBoolean;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
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

class AddressInformation extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $data = [];

    public $enable_form = false;

    public $action = CrudAction::Create; 
    public $model_id;
    
    public function render()
    {
        return view('livewire.application.address-information');
    }

    public function mount()
    {
        $this->form->fill();

        if($this->getTableQuery()->count() <= 0){
            $this->enable_form = true;
        }
    }

    public function getTableQuery()
    {
        return Address::where('account_id', accountId());
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('address_type')
                ->label('Address Type'),
            TextColumn::make('address')
                ->label('Address')
                ->formatStateUsing(fn(Address $record) => $record->getFullAddress() ),
            TextColumn::make('phone_number')
                ->label('Phone at Location'),
        ];
    }

    protected function getTableActions(): array
    {
        return [ 
            Action::make('edit')
                ->action(function(Address $record){
                    $this->model_id = $record->id;
                    $this->action = CrudAction::Update;
                    $this->enable_form = true;
                    $this->form->fill($record->toArray());
                    
                }),
            ViewColumn::make('pipe')->label('')->view('filament.tables.columns.pipe'),
            DeleteAction::make()->icon(''),
        ];
    }

    // protected function getTableHeaderActions(): array
    // {
    //     return [ 
    //         CreateAction::make()
    //             ->label('Add')
    //             ->action(function(){
    //                 $this->reset('model_id');
    //                 $this->action = CrudAction::Create;
    //                 $this->enable_form = true;
    //                 $this->form->fill();
    //             })
    //     ];
    // }


    protected function isTablePaginationEnabled(): bool 
    {
        return false;
    }

    protected function getTableEmptyStateIcon(): ?string 
    {
        return 'heroicon-o-collection';
    }
 
    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Address information';
    }
 
    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Create an address record using the form below.';
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
            Hidden::make('account_id')
                ->afterStateHydrated(function(Hidden $component, Closure $set, Closure $get, $state){
                    if(!$state){
                        $set('account_id', accountId());
                    }
                }),
            Grid::make(2)
                ->schema([
                    Grid::make(1)
                        ->columnSpan(1)
                        ->schema([
                            TextInput::make('address')
                                ->label('Street Address')
                                ->required(),
                            TextInput::make('city')
                                ->required(),
                            Select::make('state')
                                ->options(us_states())
                                ->preload()
                                ->searchable()
                                ->required(),
                            TextInput::make('zip_code')
                                ->label('ZIP Code')
                                ->minLength(4)
                                ->maxLength(5)
                                ->required(),
                        ]),
                    Grid::make(1)
                        ->columnSpan(1)
                        ->schema([
                            Select::make('address_type')
                                ->label('Address Type')
                                ->options(function(){

                                    if($this->action == CrudAction::Create){
                                        $current = Address::where('account_id', accountId())->pluck('address_type', 'address_type')->toArray();
                                        $array = AddressType::asSameArray();
                                        $types = [];

                                        foreach($array as $type)
                                        {
                                            if(!in_array($type, $current)){
                                                $types[$type] = $type;
                                            }
                                        }

                                        return $types;
                                    }

                                    return AddressType::asSameArray();
                                    
                                })
                                ->required(),
                            TextInput::make('phone_number')
                                ->label('Phone at Location')
                                ->required()
                                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('000-000-0000'))
                                ->rules([new PhoneNumberRule]),
                        ])
                ]),
        ];
    }

    public function add()
    {
        $this->reset('model_id');
        $this->action = CrudAction::Create;
        $this->enable_form = true;
        $this->form->fill();
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
            $data['account_id'] = accountId();
            Address::create($data);

            Notification::make()
                ->title('Address created successfully')
                ->success()
                ->send();

            $this->reset('data');

        }
        else{
            $model = Address::find($this->model_id);
            $model->update($data);

            Notification::make()
                ->title('Address updated successfully')
                ->success()
                ->send();

            $this->reset('data');

        }

        $this->enable_form = false;
        
    }

}
