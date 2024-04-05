<?php

namespace App\Http\Livewire\Transfer;

use App\Models\Child;
use Livewire\Component;
use App\Enums\GradeLevel;
use App\Enums\RecordType;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class TransferApplications extends Component implements HasTable
{
    use InteractsWithTable;
    
    public function render()
    {
        return view('livewire.transfer.transfer-applications');
    }

    public function getTableQuery()
    {
        return Child::where('account_id', accountId())->whereIn('current_grade', [GradeLevel::Freshman, GradeLevel::Sophomore]);
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

    protected function getTableActions(): array
    {
        return [ 
            Action::make('submitted')
                ->label('View Application')
                ->visible(function(Child $record){

                    if($record->application){
                        if($record->application->appStatus?->application_submitted){
                            return true;
                        }
                    }

                    return false;
                })
                ->url(fn(Child $record) => route('application.show', $record->application->uuid))
                ->extraAttributes(['class' => 'app-status'])
                ->color(''),
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

                    $app->appStatus()->create([
                        'application_started' => 1,
                        'application_start_date' => now()
                    ]);

                    return redirect()->route('application.form', $app->uuid);
                })
                ->hidden(fn(Child $record) => $record->submitted()),
            ViewColumn::make('pipe')
                ->label('')
                ->view('filament.tables.columns.pipe')
                ->hidden(fn(Child $record) => $record->submitted() || !$record->application),
            Action::make('delete')
                ->visible(fn(Child $record) => $record->application && !$record->submitted())
                ->requiresConfirmation()
                ->modalHeading('Delete application')
                ->modalSubheading('Are you sure you want to delete this application?')
                ->action(function(Child $record){
                    $record->application->delete();
                })
                ->icon(''),
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
}
