<?php

namespace App\Filament\Catalogo\Resources\SpotResource\Pages;

use App\Filament\Catalogo\Resources\SpotResource;
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
