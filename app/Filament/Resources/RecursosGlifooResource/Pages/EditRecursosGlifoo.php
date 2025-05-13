<?php

namespace App\Filament\Resources\RecursosGlifooResource\Pages;

use App\Filament\Resources\RecursosGlifooResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecursosGlifoo extends EditRecord
{
    protected static string $resource = RecursosGlifooResource::class;

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
