<?php
 
namespace App\Filament\Pages;
 
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard as BasePage;
use Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\ApplicationSummary;
use App\Filament\Widgets\PeopleApplicationSummaryTitle;
 
class Dashboard extends BasePage
{
    protected static ?string $title = 'Dashboard';

}