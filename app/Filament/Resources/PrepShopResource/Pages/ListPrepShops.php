<?php

namespace App\Filament\Resources\PrepShopResource\Pages;

use App\Filament\Resources\PrepShopResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrepShops extends ListRecords
{
    protected static string $resource = PrepShopResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
