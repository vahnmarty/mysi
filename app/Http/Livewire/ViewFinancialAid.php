<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use App\Models\CurrentStudentFinancialAid;  
use Filament\Forms\Concerns\InteractsWithForms;

class ViewFinancialAid extends Component implements HasForms
{
    use InteractsWithForms;

    public $acknowledged;
    
    public function render()
    {
        return view('livewire.view-financial-aid');
    }

    public function mount($uuid)
    {
        $this->notification = CurrentStudentFinancialAid::whereUuid($uuid)->firstOrFail();
    }
}
