<?php

namespace App\Filament\Resources\UnsettledApplicationResource\Pages;

use Filament\Pages\Actions;
use App\Models\UnsettledApplication;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\UnsettledApplicationResource;

class ListUnsettledApplications extends ListRecords
{
    protected static string $resource = UnsettledApplicationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('import')
                ->requiresConfirmation()
                ->label('Import Account IDs')
                ->action('importSeeder')
                ->color('secondary'),
            Actions\Action::make('clear')
                ->requiresConfirmation()
                ->label('Clear Account IDs')
                ->action('clearData')
                ->color('danger'),
            Actions\CreateAction::make()
                ->label('Add Account ID'),
        ];
    }

    public function clearData()
    {
        UnsettledApplication::truncate();
    }

    public function importSeeder()
    {
        $file = database_path('imports/AccountIDs.csv');

        $handle = fopen($file, 'r');

        // Skip the first line
        fgetcsv($handle);

        // Loop through the remaining lines
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            
            $id = $data[0];

            UnsettledApplication::firstOrCreate(['account_id' => $id]);
        }
    }
}
