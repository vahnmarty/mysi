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
                ->icon('heroicon-o-cube-transparent')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/applications?tableFilters[status][value]=incomplete")',
                ]),
            Card::make('Submitted Applications', Application::submitted()->count())
                ->icon('heroicon-o-badge-check')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/applications?tableFilters[status][value]=submitted")',
                ]),
            Card::make('Total Applications', Application::count())
                ->icon('heroicon-o-cube')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/applications")',
                ]),
            Card::make('Total Users', User::users()->count())
                ->icon('heroicon-o-user-group')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-primary-100',
                    'wire:click' => '$emitUp("goto", "admin/users")',
                ]),
            Card::make('Total Students', $this->totalStudents())
                ->icon('heroicon-o-academic-cap')
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

    public function totalStudents()
    {
        $total = Child::join('users', 'children.account_id', '=', 'users.account_id')
            ->where('current_grade', 8)
            ->count();

        return $total;
    }
}
