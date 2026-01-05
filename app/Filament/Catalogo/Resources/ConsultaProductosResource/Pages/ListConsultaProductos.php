<?php

namespace App\Filament\Catalogo\Resources\ConsultaProductosResource\Pages;

use App\Filament\Catalogo\Resources\ConsultaProductosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsultaProductos extends ListRecords
{
    protected static string $resource = ConsultaProductosResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }
}
