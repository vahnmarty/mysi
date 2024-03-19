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

    protected static ?string $pollingInterval = null;

    protected $listeners = ['goto'];

    public function canViewWidget()
    {
        return auth()->user()->isAdmin() || auth()->user()->hasRole('fa_limited');
    }

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getCards(): array
    {
        if(auth()->user()->isAdmin()){
            return $this->cardsFromAdmin();
        }

        return $this->cardsFromApplicationStatus();
    }

    public function cardsFromAdmin()
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
                ->icon('heroicon-o-clipboard-check')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-gray-300',
                    'wire:click' => '$emitUp("goto", "admin/applications?tableFilters[candidate_decision_status][value]=Accepted")',
                ]),
            Card::make('All Accepted - Declined Enrollment', 
                    DB::table('application_status')
                    ->where('application_status', NotificationStatusType::Accepted)
                    ->where('candidate_decision', 0)
                    ->where('candidate_decision_status', 'Declined')
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-badge-check')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-gray-300',
                    'wire:click' => '$emitUp("goto", "admin/applications?tableFilters[candidate_decision_status][value]=Declined")',
                ]),
        ];
    }

    public function cardsFromApplicationStatus()
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
                ->icon('heroicon-o-clipboard-check')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-gray-300',
                    'wire:click' => '$emitUp("goto", "admin/application-statuses?tableFilters[candidate_decision_status][value]=Accepted")',
                ]),
            Card::make('All Accepted - Declined Enrollment', 
                    DB::table('application_status')
                    ->where('application_status', NotificationStatusType::Accepted)
                    ->where('candidate_decision', 0)
                    ->where('candidate_decision_status', 'Declined')
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-badge-check')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-gray-300',
                    'wire:click' => '$emitUp("goto", "admin/application-statuses?tableFilters[candidate_decision_status][value]=Declined")',
                ]),
        ];
    }

    public function goto($url)
    {
        return redirect($url);
    }
}
