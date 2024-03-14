<?php

namespace App\Filament\Widgets;

use DB;
use App\Enums\NotificationStatusType;
use App\Filament\Widgets\Widget\GroupWidget;
use App\Filament\Widgets\Widget\EmptyCard;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class NotificationLettersSummary extends GroupWidget
{

    public $title = 'Notification Letter Summary';

    protected $listeners = ['goto'];

    public function canViewWidget()
    {
        return auth()->user()->isAdmin();
    }

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getCards(): array
    {
        return [
            Card::make('All Accepted - Total', 
                    DB::table('application_status')
                    ->where('application_status', NotificationStatusType::Accepted)
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-clipboard-check')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/applications?tableFilters[notification_status][value]=Accepted")',
                ]),
            Card::make('All Accepted - Letters Read', 
                    DB::table('application_status')
                    ->where('application_status', NotificationStatusType::Accepted)
                    ->whereNotNull('notification_read')
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-badge-check')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/applications?tableFilters[notification_status][value]=Accepted&tableFilters[notification_read][value]=1")',
                ]),
            EmptyCard::make('empty', 0),


            Card::make('Wait Listed - Total', 
                DB::table('application_status')
                    ->where('application_status', NotificationStatusType::WaitListed)
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-cube-transparent')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/applications?tableFilters[notification_status][value]=' . NotificationStatusType::WaitListed . ' ")',
                ]),
            Card::make('Wait Listed - Letters Read',
                DB::table('application_status')
                    ->where('application_status', NotificationStatusType::WaitListed)
                    ->whereNotNull('notification_read')
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-badge-check')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/applications?tableFilters[notification_status][value]=' . NotificationStatusType::WaitListed . '&tableFilters[notification_read][value]=1")',
                ]),

            EmptyCard::make('empty', 0),
            

            Card::make('Not Accepted - Total', 
                DB::table('application_status')
                    ->where('application_status', NotificationStatusType::NotAccepted)
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-exclamation-circle')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/applications?tableFilters[notification_status][value]=' . NotificationStatusType::NotAccepted . ' ")',
                ]),
            Card::make('Not Accepted - Letters Read',
                DB::table('application_status')
                    ->where('application_status', NotificationStatusType::NotAccepted)
                    ->whereNotNull('notification_read')
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-badge-check')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/applications?tableFilters[notification_status][value]=' . NotificationStatusType::NotAccepted . '&tableFilters[notification_read][value]=1")',
                ]),
        ];
    }

    public function goto($url)
    {
        return redirect($url);
    }
}
