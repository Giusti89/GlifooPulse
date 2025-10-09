<?php

namespace App\Filament\Catalogo\Resources\SocialsResource\Pages;

use App\Filament\Catalogo\Resources\SocialsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSocials extends ListRecords
{
    protected static string $resource = SocialsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
