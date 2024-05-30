<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;
use App\Models\FamilyDirectory;
use App\Models\Views\SiDirectoryView;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;

class ViewFamilyContactInformation extends Component implements HasTable
{
    use InteractsWithTable;
    
    public function render()
    {
        return view('livewire.page.view-family-contact-information');
    }

    public function getTableQuery()
    {
        return SiDirectoryView::where('account_id', 11718);
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('full_name'),
            BadgeColumn::make('type')
                ->colors([
                    'success' => 'STUDENT',
                    'warning' => 'GUARDIAN',
                ])
                ->sortable(),
            TextColumn::make('graduation_year')
                ->label('Graduation Year')
                ->sortable(),
        ];
    }
}
