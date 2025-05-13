<?php

namespace App\Filament\Resources\RecursosGlifooResource\Pages;

use App\Filament\Resources\RecursosGlifooResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecursosGlifoos extends ListRecords
{
    protected static string $resource = RecursosGlifooResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
