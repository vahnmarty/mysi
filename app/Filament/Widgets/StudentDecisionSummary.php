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
            Card::make('All Accepted - Accepted Enrollment', 
                    DB::table('application_status')
                    ->where('application_status', NotificationStatusType::Accepted)
                    ->where('candidate_decision', 1)
                    ->where('candidate_decision_status', 'Accepted')
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-clipboard-check'),
            Card::make('All Accepted - Declined Enrollment', 
                    DB::table('application_status')
                    ->where('application_status', NotificationStatusType::Accepted)
                    ->where('candidate_decision', 0)
                    ->where('candidate_decision_status', 'Declined')
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-badge-check'),
        ];
    }
}
