<?php

namespace App\Filament\Usuario\Resources\SocialResource\Pages;

use App\Filament\Usuario\Resources\SocialResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSocial extends CreateRecord
{
    protected static string $resource = SocialResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
