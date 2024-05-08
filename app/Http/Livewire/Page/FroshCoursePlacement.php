<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;
use App\Models\CoursePlacement;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class FroshCoursePlacement extends Component implements HasTable
{
    use InteractsWithTable;
    
    public function render()
    {
        return view('livewire.page.frosh-course-placement');
    }

    public function getTableQuery()
    {
        return CoursePlacement::where('account_id', accountId());
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('child_name')
                ->label('Name'),
            TextColumn::make('academic_year')
                ->label('Academic Year'),
            TextColumn::make('biology_placement')
                ->label('Biology Placaement'),
            TextColumn::make('english_placement')
                ->label('English Placement'),
            TextColumn::make('final_language')
                ->label('Final Language'),
            TextColumn::make('math_placement')
                ->label('Math Placement'),
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
        return 'No Available Course Placements';
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
