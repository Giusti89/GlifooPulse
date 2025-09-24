<?php

namespace App\Filament\Resources\TipoproductoResource\Pages;

use App\Filament\Resources\TipoproductoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipoproducto extends EditRecord
{
    protected static string $resource = TipoproductoResource::class;

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
