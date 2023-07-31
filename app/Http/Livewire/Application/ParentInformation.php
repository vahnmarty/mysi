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
use App\Enums\ParentSuffix;
use App\Enums\AddressLocation;
use App\Rules\PhoneNumberRule;
use App\Enums\EmploymentStatus;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\Position;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ToggleColumn;
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

    public $model;
    
    public $model_id;

    protected $messages = [
        'data.personal_email.unique' => 'This email is already associated with the other parent.  Please use a different email.',
        'data.mobile_phone.unique' => 'This phone is already associated with the other parent.  Please use a different phone.'
    ];
    
    public function render()
    {
        return view('livewire.application.parent-information')
                ->layoutData(['title' => 'Parent/Guardian Information']);
    }

    public function mount()
    {
        $this->form->fill();

        if($this->getTableQuery()->count() <= 0){

            $this->createDefaultParent();
        }
    }

    public function createDefaultParent()
    {
        $user = Auth::user();

        Parents::create([
            'account_id' => accountId(),
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'mobile_phone' => $user->phone,
            'personal_email' => $user->email,
        ]);
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
                ->label('Mobile Phone')
                ->formatStateUsing(fn($state) => format_phone($state)),
            TextColumn::make('personal_email')
                ->label("Email"),
            TextColumn::make('employer')
                ->label('Employer')
                ->wrap(),
            TextColumn::make('job_title')
                ->label('Job Title')
                ->wrap(),
            ToggleColumn::make('is_primary')
                ->label('Primary Owner')
                ->disabled( fn() => Parents::where('account_id', accountId())->count() == 1)
                ->updateStateUsing(function (Parents $record, $state): void {
                    $record->is_primary = $state;
                    $record->save();

                    $parents = Parents::where('account_id', $record->account_id )
                        ->where('id', '!=', $record->id)
                        ->update([
                            'is_primary' => !$state
                        ]);
                })
        ];
    }

    protected function getTableActions(): array
    {
        return [ 
            Action::make('edit')
                ->color('primary-blue')
                ->label(new HtmlString('<span class="text-link">Edit</span>'))
                ->action(function(Parents $record, $livewire){
                    $this->model_id = $record->id;
                    $livewire->model = $record;
                    $this->action = CrudAction::Update;
                    $this->enable_form = true;
                    $this->form->fill($record->toArray());

                    $this->emit('leftAsterisk');
                }),
            ViewColumn::make('pipe')->label('')->view('filament.tables.columns.pipe'),
            Action::make('delete')
                ->requiresConfirmation()
                ->modalHeading('Delete record')
                ->modalSubheading('Are you sure you want to delete this record?')
                ->action(function(Parents $record){
                    $parents = Parents::where('account_id', accountId())->count();
                    if($parents > 1){

                        // This is to fix unique rule
                        $record->mobile_phone = null;
                        $record->personal_email = null;
                        $record->save();

                        $record->delete();

                        if(Parents::where('account_id', accountId())->count() <= 1){
                            return redirect('parents');
                        }
                    }else{
                        Notification::make()
                            ->title('The parent record cannot be deleted.')
                            ->danger()
                            ->send();
                        return;
                    }
                })
                ->icon('')
                ->disabled(fn(Parents $record) => $record->is_primary)
                ->color('primary'),
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
        return 'Create a parent/guardian record using the form below.';
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
                                ->options(ParentSuffix::asSameArray()),
                            Toggle::make('is_primary')
                                ->label('Primary Account Owner')
                                ->rules([
                                    function () {
                                        return function (string $attribute, $value, Closure $fail) {
                                            $not_primary = !$value;
                                            $no_parents = Parents::where('account_id', accountId())->count() <= 1;
                                            $no_primary = Parents::where('account_id', accountId())->where('is_primary', true)->count();

                                            if($this->action == CrudAction::Create){
                                                if($not_primary &&  !$no_primary){
                                                    $fail("You can't disable this primary account owner.");
                                                }
                                            }else{
                                                if($not_primary && $no_parents){
                                                    $fail("You can't disable this primary account owner.");
                                                }
                                            }
                                            
                                        };
                                    },
                                ])
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
                                                $fail("Legal First Name is the same as Preferred First Name.  Please delete Preferred First Name.");
                                            }
                                        };
                                    },
                                ]),
                            TextInput::make('mobile_phone')
                                ->label('Mobile Phone')
                                ->required()
                                ->afterStateHydrated(function(Closure $set, $state){
                                    if(!$state){
                                        $set('mobile_phone', '');
                                    }
                                })
                                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(000) 000-0000'))
                                ->rules([new PhoneNumberRule])
                                //->unique('parents','mobile_phone',  fn($livewire) => $livewire->model)
                                ->default('')
                                ->maxLength(14), // 14 for Mask, but 10 is for the actual Max
                            TextInput::make('personal_email')
                                ->label('Preferred Email')
                                ->validationAttribute('Preferred Email')
                                ->email()
                                ->rules(['email:rfc,dns'])
                                // ->rules(['email:rfc,dns', 'unique:parents,personal_email,' . $this->model_id])
                                //->unique(Parents::class, 'personal_email',  fn($livewire) => $livewire->model)
                                ->required()
                                ->maxLength(255),
                            Select::make('employment_status')
                                ->label('What is your employment status?')
                                ->required()
                                ->reactive()
                                ->options(EmploymentStatus::asSameArray()),
                            TextInput::make('employer')
                                ->label(fn(Closure $get) => $get('employment_status') === EmploymentStatus::Retired ? 'Last Employer' : 'Employer')
                                ->lazy()
                                ->visible(fn(Closure $get) => in_array($get('employment_status'),  [EmploymentStatus::Employed, EmploymentStatus::Retired]) )
                                ->maxLength(100),
                            TextInput::make('job_title')
                                ->label(fn(Closure $get) => $get('employment_status') === EmploymentStatus::Retired ? 'Last Job Title' : 'Job Title')
                                ->lazy()
                                ->visible(fn(Closure $get) => in_array($get('employment_status'),  [EmploymentStatus::Employed, EmploymentStatus::Retired]) )
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
