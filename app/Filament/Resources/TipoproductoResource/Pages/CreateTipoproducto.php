<?php

namespace App\Filament\Resources\TipoproductoResource\Pages;

use App\Filament\Resources\TipoproductoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTipoproducto extends CreateRecord
{
    protected static string $resource = TipoproductoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
