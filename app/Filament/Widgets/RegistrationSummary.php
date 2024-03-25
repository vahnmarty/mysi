<?php

namespace App\Filament\Widgets;

use DB;
use App\Filament\Widgets\Widget\GroupWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class RegistrationSummary extends GroupWidget
{
    public $title = 'Registration Summary';

    protected static ?string $pollingInterval = null;

    protected $listeners = ['goto'];

    public function canViewWidget()
    {
        return auth()->user()->isAdmin();
    }

    protected function getCards(): array
    {
        return [
            Card::make('Total # Registered Students', 
                    DB::table('application_status')
                    ->where('candidate_decision', 1)
                    ->where('candidate_decision_status', 'Accepted')
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-clipboard-check')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/applications?tableFilters[notification_status][value]=Accepted")',
                ]),
            Card::make('Total # Started Registration', 
                    DB::table('application_status')
                    ->where('registration_started', 1)
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-clipboard-check')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/applications?tableFilters[notification_status][value]=Accepted")',
                ]),
            Card::make('Total # Completed Registration', 
                    DB::table('application_status')
                    ->where('registration_complete_date', 1)
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-clipboard-check')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/applications?tableFilters[notification_status][value]=Accepted")',
                ]),
        ];
    }

    
}
