<?php

namespace App\Http\Livewire\Application;

use Auth;
use App\Models\User;
use App\Enums\Gender;
use App\Enums\Suffix;
use App\Models\Child;
use App\Models\School;
use App\Models\Address;
use Livewire\Component;
use App\Enums\CrudAction;
use App\Enums\GradeLevel;
use App\Enums\ParentType;
use App\Enums\RacialType;
use App\Enums\RecordType;
use App\Enums\Salutation;
use App\Enums\AddressType;
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

class AdmissionApplication extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $data = [];

    public $enable_form = false;

    public $action = CrudAction::Create; 
    public $model_id;
    
    public function render()
    {
        return view('livewire.application.admission-application');
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
        return Child::where('account_id', accountId())->where('current_grade', GradeLevel::Grade8);
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('student_name')->formatStateUsing(fn(Child $record) => $record->getFullName() ),
            TextColumn::make('record_type_id')->label('Type'),
            TextColumn::make('current_school')->label('Current School'),
            TextColumn::make('current_grade')->label('Current Grade'),
        ];
    }

    protected function getTableActions(): array
    {
        return [ 
            TextColumn::make('status'),
            Action::make('apply')
                ->label(function(Child $record){
                    return $record->application ? 'Edit' : 'Apply';
                })
                ->action(function(Child $record){

                    if($record->application){
                        return redirect()->route('application.form', $record->application->uuid);
                    }
                    $app = $record->application()->create([
                        'account_id' => accountId(),
                        'record_type_id' => RecordType::Student
                    ]);

                    $app->status()->create([
                        'application_started' => 1,
                        'application_start_date' => now()
                    ]);

                    return redirect()->route('application.form', $app->uuid);
                }),
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
        return 'No Student Data yet';
    }
 
    protected function getTableEmptyStateDescription(): ?string
    {
        return 'You may create a a child first before applying.';
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getFormSchema(): array
    {
        return [
            Hidden::make('account_id'),
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
                                ->numeric()
                                ->required(),
                        ]),
                    Grid::make(1)
                        ->columnSpan(1)
                        ->schema([
                            Select::make('address_type')
                                ->label('Address Type')
                                ->options(AddressType::asSameArray())
                                ->required(),
                            TextInput::make('phone_number')
                                ->label('Phone at Location')
                                ->required()
                                ->mask(fn (Mask $mask) => $mask->pattern('0{0}000-000-0000'))
                                ->placeholder('+1 000-000-0000')
                                ->tel()
                                ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                        ])
                ]),
        ];
    }

    

}
