<?php

namespace App\Filament\Usuario\Resources\SpotResource\Pages;

use App\Filament\Usuario\Resources\SpotResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSpot extends CreateRecord
{
    protected static string $resource = SpotResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
