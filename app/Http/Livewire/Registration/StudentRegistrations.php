<?php

namespace App\Http\Livewire\Registration;

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
use Illuminate\View\View;
use App\Enums\AddressType;
use App\Models\Application;
use App\Models\Registration;
use App\Enums\ConditionBoolean;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class StudentRegistrations extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $data = [];

    public $enable_form = false;

    public $action = CrudAction::Create; 
    public $model_id;

    public $open;
    
    public function render()
    {
        return view('livewire.registration.student-registrations')
                ->layoutData(['title' => 'Registration']);
    }

    public function mount()
    {
        $this->open = true;

        $this->form->fill([
            'account_id' => accountId()
        ]);

        if($this->getTableQuery()->count() <= 0){
            $this->enable_form = true;
        }

        
    }

    public function getTableQuery()
    {
        return Registration::where('account_id', accountId());
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('student_name')
                ->label('Student Name')
                ->formatStateUsing(fn(Registration $record) => $record->student?->getFullName() ),
            TextColumn::make('mobile_phone')
                ->label('Mobile Phone')
                ->formatStateUsing(fn(Registration $record) => format_phone($record->student?->mobile_phone)),
            TextColumn::make('student.personal_email')
                ->label('Email'),
            TextColumn::make('student.current_school')
                ->label('Current School')
                ->wrap()
                ->formatStateUsing(fn(Registration $record) => $record->student?->getCurrentSchool()),
            TextColumn::make('student.current_grade')
                ->label('Current Grade'),
        ];
    }

    protected function getTableActions(): array
    {
        return [ 
            // Action::make('submitted')
            //     ->label('View Application')
            //     ->visible(function(Child $record){

            //         if($record->application){
            //             if($record->application->appStatus?->application_submitted){
            //                 return true;
            //             }
            //         }

            //         return false;
            //     })
            //     ->url(fn(Child $record) => route('application.show', $record->application->uuid))
            //     ->extraAttributes(['class' => 'app-status'])
            //     ->color(''),
            Action::make('edit')
                ->label(fn(Registration $record) => $record->started() ? 'Edit' : 'Register' )
                ->visible(fn(Registration $record) => !$record->completed())
                ->action(function(Registration $record){

                    if(!$record->started()){
                        $record->started_at = now();
                        $record->save();
                    }

                    return redirect()->route('registration.form', $record->uuid);
                }),
            Action::make('view')
                ->label('View')
                ->visible(fn(Registration $record) => $record->completed())
                ->url(fn(Registration $record) => route('registration.completed', $record->uuid) )
            // ViewColumn::make('pipe')
            //     ->label('')
            //     ->view('filament.tables.columns.pipe')
            //     ->hidden(fn(Child $record) => $record->submitted() || !$record->application),
            // Action::make('delete')
            //     ->visible(fn(Child $record) => $record->application && !$record->submitted())
            //     ->requiresConfirmation()
            //     ->modalHeading('Delete application')
            //     ->modalSubheading('Are you sure you want to delete this application?')
            //     ->action(function(Child $record){
            //         $record->application->delete();
            //     })
            //     ->icon(''),
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


    public function isTablePaginationEnabled(): bool 
    {
        return false;
    }

    public function getTableEmptyStateIcon(): ?string 
    {
        return 'heroicon-o-collection';
    }
 
    public function getTableEmptyStateHeading(): ?string
    {
        return 'No Available Registrants';
    }

    public function getTableEmptyStateDescription(): ?string
    {
        return 'The registration will appear once the student(s) is approved.';
    }
 
    // protected function getTableEmptyStateActions()
    // {
    //     return [
    //         CreateAction::make()
    //             ->label('Create Child')
    //             ->url('children')
    //             ->icon('heroicon-o-plus')
    //     ];
    // }


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
                                ->mask(fn (Mask $mask) => $mask->pattern('(000) 000-0000'))
                                ->placeholder('(000) 000-0000')
                                ->tel()
                                ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                        ])
                ]),
        ];
    }

    

}
