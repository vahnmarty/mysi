<?php

namespace App\Filament\Resources\AppVariableResource\Pages;

use App\Filament\Resources\AppVariableResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppVariables extends ListRecords
{
    protected static string $resource = AppVariableResource::class;

    protected function getActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
