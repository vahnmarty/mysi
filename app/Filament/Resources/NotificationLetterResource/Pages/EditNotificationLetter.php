<?php

namespace App\Filament\Resources\NotificationLetterResource\Pages;

use App\Filament\Resources\NotificationLetterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNotificationLetter extends EditRecord
{
    protected static string $resource = NotificationLetterResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),

            Actions\Action::make('view_variables')
                ->label('View Variables')
                ->url(url('admin/notification-letters/variables'))
        ];
    }
}
