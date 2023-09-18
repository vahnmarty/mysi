<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use Filament\Pages\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Resources\Pages\ViewRecord;
use App\Forms\Components\ReadonlyTextbox;
use App\Filament\Resources\ApplicationResource;

class ViewApplication extends ViewRecord
{
    protected static string $resource = ApplicationResource::class;

    protected static string $view = 'filament.resources.applications.pages.view-application';

    protected function getActions(): array
    {
        return [
            //Actions\EditAction::make(),
        ];
    }
}
