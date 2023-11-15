<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Application;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
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
}
