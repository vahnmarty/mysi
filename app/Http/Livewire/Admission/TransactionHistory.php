<?php

namespace App\Http\Livewire\Admission;

use App\Models\Payment;
use Livewire\Component;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class TransactionHistory extends Component implements HasTable
{
    use InteractsWithTable;
    
    public function render()
    {
        return view('livewire.admission.transaction-history');
    }

    public function getTableQuery()
    {
        return Payment::where('user_id', auth()->id());
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('id'),
            TextColumn::make('application_id'),
            TextColumn::make('application.student.first_name'),
            TextColumn::make('name_on_card'),
            TextColumn::make('transaction_id'),
            TextColumn::make('auth_id'),
            TextColumn::make('promo_code'),
            TextColumn::make('final_amount'),
            TextColumn::make('created_at')->dateTime(),
        ];
    }
}
