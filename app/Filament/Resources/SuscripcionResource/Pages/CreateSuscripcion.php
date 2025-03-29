<?php

namespace App\Filament\Resources\SuscripcionResource\Pages;

use App\Filament\Resources\SuscripcionResource;
use App\Models\Landing;
use App\Models\Paquete;
use App\Models\Spot;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSuscripcion extends CreateRecord
{
    protected static string $resource = SuscripcionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        
        $suscripcion = $this->record->user->suscripciones()->latest()->first();

        if ($suscripcion && $suscripcion->paquete && $suscripcion->paquete->landing) {
            $tipoLanding = $suscripcion->paquete->landing->id; 
            
        } else {
            $tipoLanding = 'default'; 
        }

        Spot::create([
            'user_id' => $this->record->user_id,
            'tipolanding' => $tipoLanding,
            'estado' => 0,
        ]);
    }
}
