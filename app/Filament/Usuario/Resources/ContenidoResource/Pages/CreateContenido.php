<?php

namespace App\Filament\Usuario\Resources\ContenidoResource\Pages;

use App\Filament\Usuario\Resources\ContenidoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContenido extends CreateRecord
{
    protected static string $resource = ContenidoResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
