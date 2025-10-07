<?php

namespace App\Filament\Catalogo\Resources\ImagenProductosResource\Pages;

use App\Filament\Catalogo\Resources\ImagenProductosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImagenProductos extends ListRecords
{
    protected static string $resource = ImagenProductosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
