<?php

namespace App\Filament\Resources\NotificationLetterResource\Pages;

use App\Filament\Resources\NotificationLetterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotificationLetters extends ListRecords
{
    protected static string $resource = NotificationLetterResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
