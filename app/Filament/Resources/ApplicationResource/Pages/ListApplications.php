<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use Closure;
use App\Filament\Resources\ApplicationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationResource::class;

    protected function getActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }

    protected function getTableActionsColumnLabel(): ?string
    {
        return 'Actions';
    }

    protected function getTableRecordActionUsing(): ?Closure
    {
        return null;
    }
}
