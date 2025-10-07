<?php

namespace App\Filament\Catalogo\Resources\ProductosResource\Pages;

use App\Filament\Catalogo\Resources\ProductosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductos extends EditRecord
{
    protected static string $resource = ProductosResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
