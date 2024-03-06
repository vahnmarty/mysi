<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\PrepShop;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class SiPrepShop extends Component implements HasTable
{
    use InteractsWithTable;
    
    public function render()
    {
        return view('livewire.si-prep-shop');
    }

    protected function getTableQuery()
    {
        return PrepShop::query();
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('date')->dateTime('F d, Y'),
            TextColumn::make('morning_schedule')
                ->label('Morning Schedule'),
            TextColumn::make('afternoon_schedule')
                ->label('Afternoon Schedule'),
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
        return 'No Available Schedule';
    }

    public function getTableEmptyStateDescription(): ?string
    {
        return 'The Admission will inform once the schedule is updated.';
    }


    protected function getTableActionsColumnLabel(): ?string
    {
        return 'Action';
    }
}
