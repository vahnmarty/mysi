<?php

namespace App\Filament\Resources\ParentResource\Pages;

use App\Filament\Resources\ParentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParent extends EditRecord
{
    protected static string $resource = ParentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
