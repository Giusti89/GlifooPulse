<?php

namespace App\Filament\Catalogo\Resources\SocialsResource\Pages;

use App\Filament\Catalogo\Resources\SocialsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSocials extends EditRecord
{
    protected static string $resource = SocialsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
