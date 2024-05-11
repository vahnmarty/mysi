<?php

namespace App\Filament\Pages;

use App\Models\Child;
use Filament\Pages\Page;
use App\Models\FamilyDirectory;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Pages\Actions\Action as PageAction;
use Filament\Tables\Concerns\InteractsWithTable;

class ManageFamilyDirectory extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Family Directory';

    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.manage-family-directory';

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function getTableQuery()
    {
        return FamilyDirectory::query();
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('account.name'),
        ];
    }

    protected function getActions(): array
    {
        return [
            PageAction::make('clear')
                ->label('Clear All')
                ->requiresConfirmation()
                ->action('clear')
                ->color('secondary'),
            PageAction::make('generate')
                ->requiresConfirmation()
                ->action('generate'),

        ];
    }

    public function generate()
    {
        foreach(Child::get() as $child)
        {
            FamilyDirectory::create([
                'account_id' => $child->account_id
            ]);
        }
    }
}
