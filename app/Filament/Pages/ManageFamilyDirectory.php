<?php

namespace App\Filament\Pages;

use App\Models\Child;
use App\Models\Account;
use App\Models\Address;
use Filament\Pages\Page;
use App\Models\FamilyDirectory;
use Filament\Tables\Filters\Filter;
use App\Jobs\ProcessFamilyDirectory;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
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

    protected function getTablePollingInterval(): ?string
    {
        return '10s';
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getTableQuery()
    {
        return FamilyDirectory::query();
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('account.account_name')
                ->searchable()
                ->sortable(),
            TextColumn::make('name')
                ->searchable()
                ->sortable(),
            BadgeColumn::make('type')
                ->colors([
                    'success' => 'STUDENT',
                    'warning' => 'PARENT',
                ])
                ->sortable(),
            TextColumn::make('email'),
            TextColumn::make('phone')
                ->formatStateUsing(fn($state) => format_phone($state)),
            TextColumn::make('address'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Filter::make('student')
                ->query(fn (Builder $query): Builder => $query->where('type', "STUDENT")),
            Filter::make('parent')
                ->query(fn (Builder $query): Builder => $query->where('type', "PARENT"))
        ];
    }

    protected function getActions(): array
    {
        return [
            PageAction::make('clear')
                ->label('Clear All')
                ->requiresConfirmation()
                ->action(fn() => FamilyDirectory::truncate() )
                ->color('secondary'),
            PageAction::make('generate')
                ->requiresConfirmation()
                ->action('generate'),

        ];
    }

    public function generate()
    {
        $accounts = Account::where('current_si_family', true)->get();

        foreach($accounts as $account)
        {
            ProcessFamilyDirectory::dispatch($account);
        }

        Notification::make()
            ->title('Processing Family Directory')
            ->body("Please wait while cron worker is running on background.  Note that this page refreshes every 10 seconds. ")
            ->warning()
            ->send();
        
    }
}
