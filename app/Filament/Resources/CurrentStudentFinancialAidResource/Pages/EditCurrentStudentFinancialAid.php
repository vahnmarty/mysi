<?php

namespace App\Filament\Resources\CurrentStudentFinancialAidResource\Pages;

use App\Filament\Resources\CurrentStudentFinancialAidResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCurrentStudentFinancialAid extends EditRecord
{
    protected static string $resource = CurrentStudentFinancialAidResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
