<?php

namespace App\Http\Livewire\Application;

use App\Models\Child;
use Livewire\Component;
use App\Enums\GradeLevel;
use App\Enums\RecordType;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class SupplementalRecommendationPage extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    
    public $done = false;

    public $data = [];
    

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
        return 'No Child Information';
    }

    public function getTableEmptyState(): ?View
    {
        return view('filament.tables.empty-state');
    }

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
                ->action(function(Child $record){

                    if($record->submitted()){
                        $app = $record->application;

                        $request = $app->supplementalRecommendationRequest()->create([
                            'child_id' => $app->child_id
                        ]);

                        return redirect('supplemental-recommendation/' . $request->uuid);
                    }

                    Notification::make()
                        ->title('Please submit an application before requesting a recommendation.')
                        ->danger()
                        ->send();
                        
                    return false;

                })
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

        ];
    }
}
