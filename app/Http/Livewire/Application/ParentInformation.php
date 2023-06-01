<?php

namespace App\Http\Livewire\Application;

use Auth;
use App\Models\User;
use App\Enums\Suffix;
use App\Models\Parents;
use Livewire\Component;
use App\Enums\ParentType;
use App\Enums\Salutation;
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
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class ParentInformation extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $data = [];
    
    public function render()
    {
        return view('livewire.application.parent-information');
    }

    public function mount()
    {
        $this->form->fill([
            'user_id' => Auth::id()
        ]);
    }

    public function getTableQuery()
    {
        return Parents::where('user_id', auth()->id());
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('relationship'),
            TextColumn::make('name')->formatStateUsing(fn(Parents $record) => $record->getFullName() ),
            TextColumn::make('mobile_phone'),
            TextColumn::make('email'),
        ];
    }

    protected function getTableActions(): array
    {
        return [ 
            Action::make('edit')
                ->action(function(Parents $record){
                    $this->form->fill($record->toArray());
                }),
            DeleteAction::make()->icon(''),
        ];
    }

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
        return 'No Parent Information yet';
    }
 
    protected function getTableEmptyStateDescription(): ?string
    {
        return 'You may create a parent using the form below.';
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getFormSchema(): array
    {
        return [
            Hidden::make('user_id'),
            Grid::make(2)
                ->schema([
                    Grid::make(1)
                        ->columnSpan(1)
                        ->schema([
                            Select::make('relationship')->options(ParentType::asSelectArray())->required(),
                            Select::make('salutation')->options(Salutation::asSelectArray())->required(),
                            TextInput::make('first_name')->required(),
                            TextInput::make('middle_name')->required(),
                            TextInput::make('last_name')->required(),
                            Select::make('suffix')->options(Suffix::asSelectArray())->required(),
                        ]),
                    Grid::make(1)
                        ->columnSpan(1)
                        ->schema([
                            TextInput::make('mobile_phone')
                                ->required(),
                                //->tel()
                                //->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                            TextInput::make('email')->label('Preferred Email')->email()->required(),
                            TextInput::make('alt_email')->label('Alternate Email')->email(),
                            TextInput::make('employer')->required(),
                            TextInput::make('job_title')->required(),
                        ])
                ]),
        ];
    }

    public function save()
    {
        $data = $this->form->getState();

        Parents::create($data);

        Notification::make()
            ->title('Parent created successfully')
            ->success()
            ->send();

        $this->reset('data');
    }

}
