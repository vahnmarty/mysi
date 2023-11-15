<?php

namespace App\Filament\Resources\UnsettledApplicationResource\Pages;

use App\Filament\Resources\UnsettledApplicationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnsettledApplication extends EditRecord
{
    protected static string $resource = UnsettledApplicationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
