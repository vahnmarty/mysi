<?php

namespace App\Filament\Resources\ContactDirectoryResource\Pages;

use App\Filament\Resources\ContactDirectoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContactDirectories extends ListRecords
{
    protected static string $resource = ContactDirectoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
