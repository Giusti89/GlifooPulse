<?php

namespace App\Filament\Usuario\Resources\ContenidoResource\Pages;

use App\Filament\Usuario\Resources\ContenidoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContenido extends EditRecord
{
    protected static string $resource = ContenidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }
}
