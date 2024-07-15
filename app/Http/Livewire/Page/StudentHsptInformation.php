<?php

namespace App\Http\Livewire\Page;

use App\Models\Child;
use Livewire\Component;
use App\Models\HsptScore;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class StudentHsptInformation extends Component implements HasTable
{
    use InteractsWithTable;
    
    public function render()
    {
        return view('livewire.page.student-hspt-information');
    }

    public function getTableQuery()
    {
        return HsptScore::where('account_id', accountId());
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('hspt_id')
                ->label('HSPT ID'),
            TextColumn::make('name')
                ->label('Student Name'),
            TextColumn::make('school')
                ->label('Name of School')
                ->wrap(),
            TextColumn::make('school_code')
                ->label('School Code'),
            TextColumn::make('classroom')
                ->label('Classroom'),
            TextColumn::make('day')
                ->label('Day'),
            TextColumn::make('time')
                ->label('Time'),
        ];
    }

    protected function getTableActions(): array
    {
        return [ 
            //Action::make('see_scores')
                //->label('See Scores')
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
        return 'No Available HSPT Information';
    }

    public function getTableEmptyStateDescription(): ?string
    {
        return 'No records available';
    }

    protected function getTableActionsColumnLabel(): ?string
    {
        return 'Action';
    }
}
