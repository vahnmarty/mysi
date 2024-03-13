<?php

namespace App\Filament\Widgets\Widget;

use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget;

class GroupWidget extends StatsOverviewWidget
{
    public $title;

    public $show = true;

    protected static string $view = 'filament.widgets.group-widget';

    public function setTitle($title)
    {
        $this->title = $title;
    }

    protected function canViewWidget()
    {
        return $this->show;
    }
}
