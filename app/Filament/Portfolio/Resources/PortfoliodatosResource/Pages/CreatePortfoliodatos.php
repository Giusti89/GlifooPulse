<?php

namespace App\Filament\Portfolio\Resources\PortfoliodatosResource\Pages;

use App\Filament\Portfolio\Resources\PortfoliodatosResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePortfoliodatos extends CreateRecord
{
    protected static string $resource = PortfoliodatosResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['tecnologias']) && is_array($data['tecnologias'])) {
            $data['tecnologias'] = json_encode($data['tecnologias']);
        }
        
        return $data;
    }
}
