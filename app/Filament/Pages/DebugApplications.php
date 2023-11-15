<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Application;
use Filament\Pages\Actions\Action;
use App\Exports\UnpaidApplications;
use Filament\Tables\Filters\Filter;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Concerns\InteractsWithTable;

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

    protected function getActions(): array
    {
        return [
            Action::make('print')
                ->label('Print Unpaid Applications')
                ->action('exportUnpaidApplications')

        ];
    }

    public function exportUnpaidApplications()
    {
        return Excel::download(new UnpaidApplications, 'unpaid-applications.xlsx');
    }
}
