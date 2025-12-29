<?php

namespace App\Filament\Portfolio\Resources\SpotResource\Pages;

use App\Filament\Portfolio\Resources\SpotResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpots extends ListRecords
{
    protected static string $resource = SpotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
