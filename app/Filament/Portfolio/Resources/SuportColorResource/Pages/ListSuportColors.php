<?php

namespace App\Filament\Portfolio\Resources\SuportColorResource\Pages;

use App\Filament\Portfolio\Resources\SuportColorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuportColors extends ListRecords
{
    protected static string $resource = SuportColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
