<?php

namespace App\Filament\Resources\PrepShopResource\Pages;

use App\Filament\Resources\PrepShopResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrepShop extends EditRecord
{
    protected static string $resource = PrepShopResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
