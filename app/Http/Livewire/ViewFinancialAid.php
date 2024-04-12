<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Checkbox;
use Filament\Notifications\Notification;
use App\Models\CurrentStudentFinancialAid;  
use Filament\Forms\Concerns\InteractsWithForms;

class ViewFinancialAid extends Component implements HasForms
{
    use InteractsWithForms;

    public $checked;
    
    public function render()
    {
        return view('livewire.view-financial-aid');
    }

    public function mount($uuid)
    {
        $this->notification = CurrentStudentFinancialAid::whereUuid($uuid)->firstOrFail();

        $this->form->fill([
            'checked' => $this->notification->fa_acknowledged()
        ]);
    }

    protected function getFormSchema(): array 
    {
        return [
            Checkbox::make('checked')
                ->columnSpan('full')
                ->required()
                ->label('By checking this box, I acknowledge the Financial Assistance letter.')
                ->lazy()
                ->required()
        ];
    }

    public function acknowledgeFinancialAid()
    {
        $this->form->getState();
        
        $notification = $this->notification;
        $notification->fa_acknowledged_at = now();
        $notification->save();

        Notification::make()
            ->title('Financial Aid Acknowledged!')
            ->success()
            ->send();

        $this->render();
    }
}
