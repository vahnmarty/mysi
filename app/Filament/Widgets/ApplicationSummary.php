<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Child;
use App\Models\Parents;
use App\Models\Application;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ApplicationSummary extends BaseWidget
{
    protected static ?string $header = 'App';

    protected $listeners = ['goto'];

    protected function getCards(): array
    {
        return [
            Card::make('Incomplete Applications', Application::incomplete()->count())
                ->color('warning')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/applications")',
                ]),
            Card::make('Submitted Applications', Application::submitted()->count())
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/applications")',
                ]),
            Card::make('Total Applications', Application::count())
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/applications")',
                ]),
            Card::make('Total Users', User::count())
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/users")',
                ]),
            Card::make('Total Students', Child::student()->count())
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/children")',
                ]),
        ];
    }

    public function goto($url)
    {
        return redirect($url);
    }
}
