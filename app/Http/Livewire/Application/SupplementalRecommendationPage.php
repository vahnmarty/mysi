<?php

namespace App\Http\Livewire\Application;

use Mail;
use Closure;
use App\Models\Child;
use App\Models\Parents;
use Livewire\Component;
use App\Enums\GradeLevel;
use App\Enums\RecordType;
use Illuminate\View\View;
use App\Rules\MaxWordCount;
use App\Mail\RecommendationRequest;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use App\Forms\Components\WordTextArea;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Models\SupplementalRecommendation;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\SupplementalRecommendationRequest;

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
            Action::make('request')
                ->label(function(Child $record){
                    if($record->recommendations()->count()){
                        if($record->recommendations()->received()->first()){
                            return 'Rec Received';
                        }
                    }else{
                        return 'Request Rec';
                    }
                })
                ->action(function(Child $record, $livewire){

                    if($record->submitted()){
                        $app = $record->application;

                        $this->model_id = $record->id;
                        $livewire->model = $record;
                        $this->enable_form = true;

                        $this->form->fill([
                            'child_id' => $record->id,
                            'application_id' => $record->application?->id
                        ]);

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
                ->visible(function(Child $record){
                    if($record->submittedApplication){
                        if($record->recommendations()->received()->first()){
                            return true;
                        }
                        if($record->recommendations()->count()){
                            return false;
                        }else{
                            return true;
                        }
                    }

                    return false;
                })
                // ->hidden(function(Child $record){
                //     return $record->recommendations()->count() && !$record->recommendations()->received()->first();
                // })
                ->disabled(fn(Child $record) => $record->recommendations()->received()->count()),


            Action::make('cancel')
                ->requiresConfirmation()
                ->requiresConfirmation()
                ->modalHeading('Cancel Request?')
                ->modalSubheading('Are you sure you want to cancel this recommendation request?')
                ->modalButton('Yes')
                ->label(function(Child $record){
                    return 'Cancel Rec';
                })
                ->action(function(Child $record, $livewire){

                    $record->recommendations()->delete();

                    Notification::make()
                        ->title('Recommendation Request deleted successfully!')
                        ->success()
                        ->send();
                        

                })
                ->visible(function(Child $record){
                    if($record->submittedApplication){
                        if($record->recommendations()->received()->first()){
                            return false;
                        }
                        if($record->recommendations()->count()){
                            return true;
                        }

                    }

                    return false;
                })
                // ->hidden(function(Child $record){
                //     return $record->recommendations()->received()->count() || $record->recommendations()->count() == 0;
                // })
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
                    Hidden::make('child_id'),
                    Hidden::make('application_id'),
                    Select::make('parent_id')
                        ->label('Requester')
                        ->options(Parents::where('account_id', accountId())->get()->pluck('full_name', 'id'))
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function(Closure $set, $state){
                            $parent = Parents::findOrFail($state);

                            $set('requester_email', $parent->personal_email);
                            $set('requester_name', $parent->getFullName());
                        })
                        ->columnSpan(2),
                    Hidden::make('requester_name')->reactive(),
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
                    WordTextArea::make('message')
                        ->maxLength(2250)
                        ->required()
                        ->columnSpan(2)
                        ->helperText('Please limit your answer to 250 words.')
                        ->rows(13)
                        ->lazy()
                        ->required()
                        ->wordLimit(250)
                        ->rules([
                            new MaxWordCount(250,300)
                        ])
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
        $data = $this->form->getState();

        $recommendation = new SupplementalRecommendation;
        $recommendation->fill($data);
        $recommendation->date_requested = date('Y-m-d');
        $recommendation->save();

        Mail::to($data['recommender_email'])
            ->send(new RecommendationRequest($recommendation));

        Notification::make()
            ->title('Recommendation Request Sent!')
            ->success()
            ->send();

        $this->form->fill();

        $this->enable_form = false;
        
        return true;
    }
}
