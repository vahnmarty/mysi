<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Parents;
use App\Models\Application;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Users', User::count()),
            Card::make('Parents', Parents::count()),
            Card::make('Applicants', Application::count()),
        ];
    }
}
