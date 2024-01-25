<?php

namespace App\Http\Livewire\Admission;

use Livewire\Component;
use App\Models\Application;
use App\Models\NotificationMessage;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class ApplicationNotification extends Component implements HasTable
{
    use InteractsWithTable;
    
    public function render()
    {
        return view('livewire.admission.application-notification');
    }

    public function mount()
    {
        $notifications = NotificationMessage::where('account_id', accountId())->get();
        
    }

    public function getTableQuery()
    {
        return  NotificationMessage::where('account_id', accountId());
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('application.student_name')
                ->label('Student Name')
                ->formatStateUsing(fn(NotificationMessage $record) => $record->application->student->getFullName() ),
            TextColumn::make('mobile_phone')
                ->label('Mobile Phone')
                ->formatStateUsing(fn(NotificationMessage $record) => format_phone($record->application->student->mobile_phone)),
            TextColumn::make('personal_email')
                ->label('Email')
                ->formatStateUsing(fn(NotificationMessage $record) => $record->application->student->personal_email),
            TextColumn::make('current_school')
                ->label('Current School')
                ->wrap()
                ->formatStateUsing(fn(NotificationMessage $record) => $record->application->student->getCurrentSchool()),
            TextColumn::make('current_grade')
                ->label('Current Grade')
                ->formatStateUsing(fn(NotificationMessage $record) => $record->application->student->current_grade),
        ];
    }

    protected function getTableActions(): array
    {
        return [ 
            Action::make('read')
                ->label('Read Letter')
                ->action(function(NotificationMessage $record){
                    $appStatus = $record->application->appStatus;

                    if(!$appStatus->notification_read){
                        $appStatus->update([
                            'notification_read' => true,
                            'notification_read_date' => now(),
                            'candidate_decision_status' => 'Notification Read'
                        ]);
                    }


                    return redirect()->route('notifications.show', $record->uuid);
                })
                ->extraAttributes(['class' => 'app-status'])
                ->color(''),
        ];
    }

    protected function isTablePaginationEnabled()
    {
        return false;
    }
}
