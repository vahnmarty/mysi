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
        return view('livewire.application.children-information')
            ->layoutData(['title' => 'Children Information']);
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
        return Child::where('account_id', accountId());
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('student_name')->label('Student Name')->formatStateUsing(fn(Child $record) => $record->getFullName() ),
            TextColumn::make('mobile_phone')
                ->label('Mobile Phone')
                ->formatStateUsing(fn($state) => format_phone($state)),
            TextColumn::make('personal_email')->label('Personal Email'),
            TextColumn::make('current_school')
                ->label('Current School')
                ->formatStateUsing(fn(Child $record) => $record->getCurrentSchool()),
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
        return 'heroicon-o-user';
    }
 
    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Child Information';
    }
 
    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Create a child record using the form below.';
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
                Grid::make(1)
                    ->columnSpan(1)
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
                            ->label('Legal Middle Name'),
                        TextInput::make('last_name')
                            ->label('Legal Last Name')
                            ->required(),
                        Select::make('suffix')
                            ->label('Suffix')
                            ->options(Suffix::asSameArray()),
                        TextInput::make('preferred_first_name')
                            ->label('Preferred First Name (Must be different from Legal First Name)')
                            ->rules([
                                function () {
                                    return function (string $attribute, $value, Closure $fail) {
                                        if ($value === $this->data['first_name']) {
                                            $fail("Legal First Name is the same as Preferred First Name.  Please delete Preferred First Name.");
                                        }
                                    };
                                },
                            ]),
                    ]),
                Grid::make(1)
                    ->columnSpan(1)
                    ->schema([
                        Select::make('gender')
                            ->options(Gender::asSelectArray())
                            ->required(),
                        TextInput::make('personal_email')
                            ->label('Personal Email')
                            ->email()
                            ->rules(['email:rfc,dns'])
                            ->required(),
                        TextInput::make('mobile_phone')
                            ->label("Mobile Phone (If none, use a parent’s mobile phone.)")
                            ->required()
                            ->afterStateHydrated(function(Closure $set, $state){
                                if(!$state){
                                    $set('mobile_phone', '');
                                }
                            })
                            ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000'))
                            ->rules([new PhoneNumberRule]),
                        Select::make('current_school')
                            ->label('Current School')
                            ->options(['Not Listed' => 'Not Listed'] + School::active()->get()->pluck('name', 'name')->toArray())
                            ->preload()
                            ->searchable()
                            ->reactive()
                            ->required(),
                        TextInput::make('current_school_not_listed')
                            ->label('If not listed, add it here')
                            ->lazy()
                            ->required()
                            ->placeholder('Enter School Name')
                            ->hidden(fn (Closure $get) => $get('current_school') !== 'Not Listed'),
                        Select::make('current_grade')
                            ->label('Current Grade')
                            ->options(GradeLevel::asSameArray())
                            ->required(),
                    ])
            ])
            
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

            Child::create($data);

            Notification::make()
                ->title('Child record is created.')
                ->success()
                ->send();

            $this->reset('data');

        }
        else{
            $model = Child::find($this->model_id);
            $model->update($data);

            Notification::make()
                ->title('Child record is created.')
                ->success()
                ->send();

            $this->reset('data');

        }

        $this->enable_form = false;
        
    }

}
