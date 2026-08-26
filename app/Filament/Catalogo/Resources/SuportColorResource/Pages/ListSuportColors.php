<?php

namespace App\Filament\Catalogo\Resources\SuportColorResource\Pages;

use App\Filament\Catalogo\Resources\SuportColorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuportColors extends ListRecords
{
    protected static string $resource = SuportColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
