<?php

namespace App\Filament\Resources\TipoproductoResource\Pages;

use App\Filament\Resources\TipoproductoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipoproductos extends ListRecords
{
    protected static string $resource = TipoproductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Crear tipo de producto'),
        ];
    }
}
