<?php

namespace App\Filament\Widgets\Widget;

use Illuminate\View\View;
use Filament\Widgets\StatsOverviewWidget\Card;

class EmptyCard extends Card {

    
    public function render(): View
    {
        return view('filament.widgets.empty-card-widget', $this->data());
    }
}