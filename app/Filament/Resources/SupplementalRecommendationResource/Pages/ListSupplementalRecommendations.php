<?php

namespace App\Filament\Resources\SupplementalRecommendationResource\Pages;

use App\Filament\Resources\SupplementalRecommendationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupplementalRecommendations extends ListRecords
{
    protected static string $resource = SupplementalRecommendationResource::class;

    protected function getActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
