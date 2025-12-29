<?php

namespace App\Filament\Portfolio\Resources\PortfoliodatosResource\Pages;

use App\Filament\Portfolio\Resources\PortfoliodatosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPortfoliodatos extends EditRecord
{
    protected static string $resource = PortfoliodatosResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Convertir el array de tecnologías a JSON
        if (isset($data['tecnologias']) && is_array($data['tecnologias'])) {
            $data['tecnologias'] = json_encode($data['tecnologias']);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
