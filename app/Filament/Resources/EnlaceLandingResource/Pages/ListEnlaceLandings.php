<?php

namespace App\Filament\Resources\EnlaceLandingResource\Pages;

use App\Filament\Resources\EnlaceLandingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEnlaceLandings extends ListRecords
{
    protected static string $resource = EnlaceLandingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
