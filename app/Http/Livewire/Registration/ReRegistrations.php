<?php

namespace App\Http\Livewire\Registration;

use Closure;
use App\Models\Child;
use Livewire\Component;
use App\Enums\GradeLevel;
use App\Enums\RecordType;
use App\Models\Registration;
use App\Models\ReRegistration;
use Filament\Tables\Actions\Action;
use App\Mail\DeclinedReRegistration;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Select;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class ReRegistrations extends Component implements HasTable
{
    use InteractsWithTable;

    public $open;
    
    public function render()
    {
        return view('livewire.registration.re-registrations');
    }

    public function mount()
    {
        $this->open = ReRegistration::isEnabled();
    }

    public function getTableQuery()
    {
        $schools = [
            'St. Ignatius College Preparatory'
        ];

        return Child::where('account_id', accountId())
            ->whereIn('current_grade', [GradeLevel::Freshman, GradeLevel::Sophomore, GradeLevel::Junior])
            ->whereIn('current_school', $schools );
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
            Action::make('edit')
                ->label('Edit')
                ->url(fn(Child $record) => route('registration.re.form', $record->reregistration->uuid))
                ->visible(fn(Child $record) => $record->reregistration && !$record->reregistration->completed() && !$record->reregistration->declined())       
                ->mountUsing(fn (ComponentContainer $form, Child $record) => $form->fill()),
            Action::make('register')
                ->label('Register')
                ->visible(fn(Child $record) => !$record->reregistration)
                ->url(fn(Child $record) => route('registration.re.select', ['id' => $record->id])),
            Action::make('declined')
                ->label('Declined')
                ->visible(fn(Child $record) => $record->reregistration ? !$record->reregistration->attending_si : false )
                ->disabled(),
            Action::make('view')
                ->label('View')
                ->visible(fn(Child $record) => $record->reregistration?->completed())
                ->url(fn(Child $record) => route('registration.re.completed', $record->reregistration?->uuid) )
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

    public function getTableEmptyStateDescription(): ?string
    {
        return 'This re-registration will only display student(s) from SI.';
    }

    protected function getTableActionsColumnLabel(): ?string
    {
        return 'Action';
    }
}
