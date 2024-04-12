<?php

namespace App\Http\Livewire\Registration;

use Livewire\Component;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use App\Models\CurrentStudentFinancialAid;
use Filament\Tables\Concerns\InteractsWithTable;

class FinancialAidNotifications extends Component implements HasTable
{
    use InteractsWithTable;

    public function render()
    {
        return view('livewire.registration.financial-aid-notifications');
    }

    public function mount()
    {
    }

    public function getTableQuery()
    {
        return CurrentStudentFinancialAid::where('account_id', accountId());
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('student.student_name')
                ->label('Student Name')
                ->formatStateUsing(fn(CurrentStudentFinancialAid $record) => $record->student->getFullName() ),
            TextColumn::make('student.mobile_phone')
                ->label('Mobile Phone')
                ->formatStateUsing(fn(CurrentStudentFinancialAid $record) => format_phone($record->student->mobile_phone)),
            TextColumn::make('student.personal_email')
                ->label('Email'),
            TextColumn::make('student.current_school')
                ->label('Current School')
                ->wrap()
                ->formatStateUsing(fn(CurrentStudentFinancialAid $record) => $record->student?->getCurrentSchool()),
            TextColumn::make('student.current_grade')
                ->label('Current Grade'),
        ];
    }

    protected function getTableActions(): array
    {
        return [ 
            Action::make('view')
                ->label('View')
                ->url(fn(CurrentStudentFinancialAid $record) => route('notifications.fa.show', ['uuid' => $record->uuid]))
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
        return 'No Available Registrants';
    }

    public function getTableEmptyStateDescription(): ?string
    {
        return 'The registration will appear once the student(s) is approved.';
    }
}
