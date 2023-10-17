<?php

namespace App\Http\Livewire\Notifications;

use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Concerns\InteractsWithForms;

class FinancialAid extends Component implements HasForms
{
    use InteractsWithForms;
    
    public $checked;
    
    public function render()
    {
        return view('livewire.notifications.financial-aid');
    }

    public function getFormSchema()
    {
        return [
            Checkbox::make('checked')
                ->columnSpan('full')
                ->label('By checking the box, I acknowledged the Financial Aid')
                ->lazy()
                ->required()
        ];
    }

    public function acknowledge()
    {
        
    }
}
