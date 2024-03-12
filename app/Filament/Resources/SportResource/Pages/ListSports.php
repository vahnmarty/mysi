<?php

namespace App\Filament\Resources\SportResource\Pages;

use App\Filament\Resources\SportResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSports extends ListRecords
{
    protected static string $resource = SportResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
