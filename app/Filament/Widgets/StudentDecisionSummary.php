<?php

namespace App\Filament\Widgets;

use DB;
use Filament\Widgets\Widget;
use App\Enums\NotificationStatusType;
use App\Filament\Widgets\Widget\EmptyCard;
use App\Filament\Widgets\Widget\GroupWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StudentDecisionSummary extends GroupWidget
{
    public $title = 'Student Decision Summary';

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getCards(): array
    {
        return [
            Card::make('All Accepted - Accepted Enrollment', 
                    DB::table('application_status')
                    ->where('application_status', NotificationStatusType::Accepted)
                    ->where('candidate_decision', 1)
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-clipboard-check'),
            Card::make('All Accepted - Declined Enrollment', 
                    DB::table('application_status')
                    ->where('application_status', NotificationStatusType::Accepted)
                    ->where('candidate_decision', 0)
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-badge-check'),
            EmptyCard::make('empty', 0),


            Card::make('Accepted Enrollment - Deposit Paid', 
                DB::table('application_status')
                    ->select('application_status', 'payments.*')
                    ->join('payments', 'application_status.application_id', '=', 'payments.application_id')
                    ->where('payment_type', 'RegFee')
                    ->where('application_status', NotificationStatusType::Accepted)
                    ->whereNotNull('transaction_id')
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-cube-transparent'),
            Card::make('Accepted Enrollment - Deposit Not Paid', 
                DB::table('application_status')
                    ->select('application_status', 'payments.*')
                    ->join('payments', 'application_status.application_id', '=', 'payments.application_id')
                    ->where('application_status', NotificationStatusType::Accepted)
                    ->where('payment_type', 'RegFee')
                    ->whereNull('transaction_id')
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-cube-transparent'),

            EmptyCard::make('empty', 0),
        ];
    }
}
