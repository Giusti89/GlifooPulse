<?php

namespace App\Filament\Catalogo\Resources\CategoriaResource\Pages;

use App\Filament\Catalogo\Resources\CategoriaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoria extends EditRecord
{
    protected static string $resource = CategoriaResource::class;
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
