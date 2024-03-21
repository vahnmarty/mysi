<?php

namespace App\Filament\Resources\ApplicationStatusResource\Pages;

use Filament\Pages\Actions;
use App\Models\ApplicationStatus;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ApplicationStatusResource;

class ListApplicationStatuses extends ListRecords
{
    protected static string $resource = ApplicationStatusResource::class;

    protected function getTableQuery(): Builder
    {
        return ApplicationStatus::join('applications', 'application_status.application_id', 'applications.id');
    }

    protected function getActions(): array
    {
        return [
            
        ];
    }
}
