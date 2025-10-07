<?php

namespace App\Filament\Catalogo\Resources\ImagenProductosResource\Pages;

use App\Filament\Catalogo\Resources\ImagenProductosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImagenProductos extends EditRecord
{
    protected static string $resource = ImagenProductosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
     protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
