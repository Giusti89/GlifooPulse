<?php

namespace App\Filament\Usuario\Resources\ContenidoResource\Pages;

use App\Filament\Usuario\Resources\ContenidoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContenidos extends ListRecords
{
    protected static string $resource = ContenidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }
}
