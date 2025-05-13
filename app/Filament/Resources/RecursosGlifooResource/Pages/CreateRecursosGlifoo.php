<?php

namespace App\Filament\Resources\RecursosGlifooResource\Pages;

use App\Filament\Resources\RecursosGlifooResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRecursosGlifoo extends CreateRecord
{
    protected static string $resource = RecursosGlifooResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
