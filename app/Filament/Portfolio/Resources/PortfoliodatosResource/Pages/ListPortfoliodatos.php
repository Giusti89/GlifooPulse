<?php

namespace App\Filament\Portfolio\Resources\PortfoliodatosResource\Pages;

use App\Filament\Portfolio\Resources\PortfoliodatosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPortfoliodatos extends ListRecords
{
    protected static string $resource = PortfoliodatosResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }
}
