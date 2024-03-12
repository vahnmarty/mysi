<?php

namespace App\Filament\Resources\AppVariableResource\Pages;

use App\Filament\Resources\AppVariableResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppVariable extends EditRecord
{
    protected static string $resource = AppVariableResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
