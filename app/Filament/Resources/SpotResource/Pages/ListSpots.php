<?php

namespace App\Filament\Resources\SpotResource\Pages;

use App\Filament\Resources\SpotResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListSpots extends ListRecords
{
    protected static string $resource = SpotResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }
}
