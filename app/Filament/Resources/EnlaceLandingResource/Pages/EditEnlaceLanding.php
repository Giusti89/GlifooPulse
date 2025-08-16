<?php

namespace App\Filament\Resources\EnlaceLandingResource\Pages;

use App\Filament\Resources\EnlaceLandingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnlaceLanding extends EditRecord
{
    protected static string $resource = EnlaceLandingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
