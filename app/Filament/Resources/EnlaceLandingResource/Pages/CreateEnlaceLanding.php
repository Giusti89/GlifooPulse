<?php

namespace App\Filament\Resources\EnlaceLandingResource\Pages;

use App\Filament\Resources\EnlaceLandingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEnlaceLanding extends CreateRecord
{
    protected static string $resource = EnlaceLandingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
