<?php

namespace App\Http\Livewire\Application;

use Auth;
use Closure;
use App\Models\User;
use App\Enums\Suffix;
use App\Models\Parents;
use Livewire\Component;
use App\Enums\CrudAction;
use App\Enums\ParentType;
use App\Enums\Salutation;
use App\Enums\AddressLocation;
use App\Rules\PhoneNumberRule;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class ParentInformation extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $data = [];

    public $enable_form = false;

    public $action = CrudAction::Create; 
    public $model_id;
    
    public function render()
    {
        return view('livewire.application.parent-information')
                ->layoutData(['title' => 'Parent/Guardian Information']);
    }

    public function mount()
    {
        $this->form->fill();

        if($this->getTableQuery()->count() <= 0){
            $this->enable_form = true;
            return;
        }
    }

    public function getTableQuery()
    {
        return Parents::where('account_id', accountId());
    }

    protected function getTableColumns(): array 
    {
        return [
            //TextColumn::make('relationship_type')->label('Relationship'),
            TextColumn::make('name')
                ->label('Parent/Guardian Name')
                ->formatStateUsing(fn(Parents $record) => $record->getFullName() ),
            TextColumn::make('mobile_phone')
                ->label('Mobile Phone'),
            TextColumn::make('personal_email')
                ->label("Email"),
            TextColumn::make('employer')
                ->label('Employer'),
            TextColumn::make('job_title')
                ->label('Job Title'),
        ];
    }

    protected function getTableActions(): array
    {
        return [ 
            Action::make('edit')
                ->color('primary-blue')
                ->label(new HtmlString('<span class="text-link">Edit</span>'))
                ->action(function(Parents $record){
                    $this->model_id = $record->id;
                    $this->action = CrudAction::Update;
                    $this->enable_form = true;
                    $this->form->fill($record->toArray());
                }),
            DeleteAction::make()->icon('')->color('danger'),
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
        return 'heroicon-o-bookmark';
    }
 
    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Parent/Guardian Information';
    }
 
    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Create a Parent/Guardian record using the form below.';
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
                            Select::make('salutation')->options(Salutation::asSameArray())->required(),
                            TextInput::make('first_name')
                                ->label('Legal First Name')
                                ->required()
                                ->maxLength(40),
                            TextInput::make('middle_name')
                                ->label('Legal Middle Name')
                                ->maxLength(40),
                            TextInput::make('last_name')
                                ->label('Legal Last Name')
                                ->required()
                                ->maxLength(40),
                            Select::make('suffix')
                                ->label('Suffix')
                                ->options(Suffix::asSameArray()),
                        ]),
                    Grid::make(1)
                        ->columnSpan(1)
                        ->schema([
                            TextInput::make('preferred_first_name')
                                ->label('Preferred First Name (Must be different from Legal First Name)')
                                ->maxLength(40)
                                ->lazy()
                                ->rules([
                                    function () {
                                        return function (string $attribute, $value, Closure $fail) {
                                            if ($value === $this->data['first_name']) {
                                                $fail("Legal First Name is the same as Preferred First Name.  Please delete Preferred First Name");
                                            }
                                        };
                                    },
                                ]),
                            TextInput::make('mobile_phone')
                                ->label('Mobile Phone')
                                ->required()
                                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000'))
                                ->rules([new PhoneNumberRule])
                                ->default('')
                                ->maxLength(14), // 14 for Mask, but 10 is for the actual Max
                            TextInput::make('personal_email')
                                ->label('Preferred Email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            TextInput::make('employer')
                                ->label('Employer')
                                ->maxLength(100),
                            TextInput::make('job_title')
                                ->label('Job Title')
                                ->maxLength(128),
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
            Parents::create($data);

            Notification::make()
                ->title('Parent created successfully')
                ->success()
                ->send();

            $this->reset('data');

        }
        else{
            $model = Parents::find($this->model_id);
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
