<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;
use App\Models\HsptScore;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class StudentHsptScores extends Component implements HasTable
{
    use InteractsWithTable;
    
    public function render()
    {
        return view('livewire.page.student-hspt-scores');
    }

    public function getTableQuery()
    {
        return HsptScore::where('account_id', accountId());
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('name'),
            TextColumn::make('school'),
            TextColumn::make('grade'),
            TextColumn::make('academic_year'),
            TextColumn::make('composite'),
            TextColumn::make('quantitative'),
            TextColumn::make('math'),
            TextColumn::make('verbal'),
            TextColumn::make('reading'),
            TextColumn::make('language'),
        ];
    }

    protected function getTableActions(): array
    {
        return [ 
            Action::make('see_scores')
                ->label('See Scores')
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
        return 'No Available HSPT Scores';
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
