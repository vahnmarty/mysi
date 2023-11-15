<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Application;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;

class DebugApplications extends Page implements HasTable
{

    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.debug-applications';

    public function mount()
    {
        
    }

    public function getTableQuery()
    {
        return Application::query();
    }

    protected function getTableColumns(): array 
    {
        return [
            TextColumn::make('id')
                ->sortable()
                ->searchable(),
            TextColumn::make('account_id')
                ->sortable()
                ->searchable(),
            TextColumn::make('account.account_name')
                ->sortable()
                ->searchable(),
            TextColumn::make('payment.id')
                ->label('payment_id')
                ->sortable()
                ->searchable(),
            TextColumn::make('payment.transaction_id')
                ->label('transaction_id')
                ->sortable()
                ->searchable(),
            TextColumn::make('payment.promo_code')
                ->label('promo_code')
                ->sortable()
                ->searchable(),
            TextColumn::make('payment.total_amount')
                ->label('total_amount')
                ->sortable()
                ->searchable(),
            TextColumn::make('appStatus.id')
                ->label('appstatus_id')
                ->sortable()
                ->searchable(),
            TextColumn::make('appStatus.application_submit_date')
                ->label('date_submitted')
                ->sortable()
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Filter::make('submitted')
                ->query(fn (Builder $query): Builder => $query->submitted()),
            Filter::make('with_promo_code')
                ->query(fn (Builder $query): Builder => $query->hasPromoCode()),
            Filter::make('no_transaction')
                ->query(fn (Builder $query): Builder => $query->noTransaction())
        ];
    }
}
