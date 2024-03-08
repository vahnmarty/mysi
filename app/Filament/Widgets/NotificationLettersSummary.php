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
                ->icon('heroicon-o-clipboard-check'),
            Card::make('All Accepted - Letters Read', 
                    DB::table('application_status')
                    ->where('application_status', NotificationStatusType::Accepted)
                    ->whereNotNull('notification_read')
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-badge-check'),
            EmptyCard::make('empty', 0),


            Card::make('Wait Listed - Total', 
                DB::table('application_status')
                    ->where('application_status', NotificationStatusType::WaitListed)
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-cube-transparent'),
            Card::make('Wait Listed - Letters Read',
                DB::table('application_status')
                    ->where('application_status', NotificationStatusType::WaitListed)
                    ->whereNotNull('notification_read')
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-badge-check'),

            EmptyCard::make('empty', 0),
            

            Card::make('Not Accepted - Total', 
                DB::table('application_status')
                    ->where('application_status', NotificationStatusType::NotAccepted)
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-exclamation-circle'),
            Card::make('Not Accepted - Letters Read',
                DB::table('application_status')
                    ->where('application_status', NotificationStatusType::NotAccepted)
                    ->whereNotNull('notification_read')
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-badge-check'),
        ];
    }
}
