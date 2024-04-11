<?php

namespace App\Filament\Widgets;

use DB;
use App\Models\Child;
use App\Enums\GradeLevel;
use App\Filament\Widgets\Widget\GroupWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ReRegistrationSummary extends GroupWidget
{
    public $title = 'Re-Registration Summary';

    protected static ?string $pollingInterval = null;

    protected $listeners = ['goto'];

    public function canViewWidget()
    {
        return auth()->user()->isAdmin();
    }

    protected function getCards(): array
    {
        $si_school = 'St. Ignatius College Preparatory';

        return [
            Card::make('Total # SI Students', 
                Child::whereIn('current_grade', [GradeLevel::Freshman, GradeLevel::Sophomore])
                    ->where('current_school', $si_school )
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-clipboard-check'),
            Card::make('Total # Re-Registration Started', 
                    DB::table('re_registrations')
                    ->whereNotNull('started_at')
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-clipboard-check'),
            Card::make('Total # Re-Registration Completed', 
                    DB::table('re_registrations')
                    ->whereNotNull('completed_at')
                    ->count()
                )
                ->color('warning')
                ->icon('heroicon-o-clipboard-check')
        ];
    }

    public function goto($url)
    {
        return redirect($url);
    }

    
}
