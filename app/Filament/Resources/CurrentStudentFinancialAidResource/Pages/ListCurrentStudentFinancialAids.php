<?php

namespace App\Filament\Resources\CurrentStudentFinancialAidResource\Pages;

use App\Filament\Resources\CurrentStudentFinancialAidResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCurrentStudentFinancialAids extends ListRecords
{
    protected static string $resource = CurrentStudentFinancialAidResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Record'),
        ];
    }
}
