<?php

namespace App\Http\Livewire\Admission;

use Livewire\Component;
use App\Models\Application;
use App\Models\NotificationMessage;
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

        if( count($notifications) == 1){
            return redirect()->route('notifications.show', $notifications[0]->uuid);
        }
        
    }

    public function getTableQuery()
    {
        return  NotificationMessage::where('account_id', accountId());
    }

    protected function getTableColumns(): array 
    {
        return [
            
        ];
    }

    protected function isTablePaginationEnabled()
    {
        return false;
    }
}
