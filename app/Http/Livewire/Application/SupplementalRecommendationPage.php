<?php

namespace App\Http\Livewire\Application;

use Closure;
use App\Models\Child;
use App\Models\Parents;
use Livewire\Component;
use App\Enums\GradeLevel;
use App\Enums\RecordType;
use Illuminate\View\View;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class SupplementalRecommendationPage extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    
    public $done = false;
    public $enable_form;
    public $data = [];
    public $model, $model_id;
    

    public function render()
    {
        return view('livewire.application.supplemental-recommendation-page');
    }

    public function mount()
    {
        $this->form->fill();
    }

    # Table

    public function getTableQuery()
    {
        return Child::where('account_id', accountId())->where('current_grade', GradeLevel::Grade8);
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('student_name')
                ->label('Student Name')
                ->formatStateUsing(fn(Child $record) => $record->getFullName() ),
            TextColumn::make('mobile_phone')
                ->label('Mobile Phone')
                ->formatStateUsing(fn($state) => format_phone($state)),
            TextColumn::make('personal_email')
                ->label('Email'),
            TextColumn::make('current_school')
                ->label('Current School')
                ->wrap()
                ->formatStateUsing(fn(Child $record) => $record->getCurrentSchool()),
            TextColumn::make('current_grade')
                ->label('Current Grade'),
        ];
    }

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
        return 'No Application data.';
    }

    // public function getTableEmptyState(): ?View
    // {
    //     return view('filament.tables.empty-state');
    // }

    protected function getTableActionsColumnLabel(): ?string
    {
        return 'Action';
    }

    protected function getTableActions(): array
    {
        return [ 
            Action::make('apply')
                ->label(function(Child $record){
                    if($record->submitted()){
                        return  'Request Rec';
                    }
                        
                    return '⚠️ Cancel Rec';
                    
                })
                ->action(function(Child $record, $livewire){

                    if($record->submitted()){
                        $app = $record->application;

                        $this->model_id = $record->id;
                        $livewire->model = $record;
                        $this->enable_form = true;
                        $this->form->fill($record->toArray());

                        return true;
                    }

                    else{
                        Notification::make()
                            ->title('Please submit an application before requesting a recommendation.')
                            ->danger()
                            ->send();
                            
                        return false;
                    }

                })
                ->hidden(fn(Child $record) => $record->submittedApplication ? false : true )
        ];
    }



    # Form

    protected function getFormStatePath(): string 
    {
        return 'data';
    }

    protected function getFormSchema()
    {
        return [
            Grid::make(2)
                ->schema([
                    Select::make('requester')
                        ->label('Requester')
                        ->options(Parents::where('account_id', accountId())->get()->pluck('full_name', 'id'))
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function(Closure $set, $state){
                            $parent = Parents::findOrFail($state);

                            $set('requester_email', $parent->personal_email);
                        })
                        ->columnSpan(2),
                    TextInput::make('requester_email')
                        ->label('Requester Email')
                        ->email()
                        ->lazy()
                        ->required()
                        ->disabled()
                        ->columnSpan(2),
                    TextInput::make('recommender_first_name')
                        ->label('First Name of Recommender')
                        ->required(),
                    TextInput::make('recommender_last_name')
                        ->label('Last Name of Recommender')
                        ->required(),
                    TextInput::make('recommender_email')
                        ->label('Recommender Email')
                        ->email()
                        ->required()
                        ->columnSpan(2),
                    Textarea::make('message')
                        ->maxLength(2000)
                        ->required()
                        ->columnSpan(2)
                ])
        ];
    }

    public function cancel()
    {
        $this->form->fill();

        $this->enable_form = false;
    }

    public function save()
    {
        Notification::make()
            ->title('Feature under Maintenance')
            ->body('Sorry, the Supplemental Recommendation Page is not yet available at the moment.')
            ->danger()
            ->send();
    }
}
