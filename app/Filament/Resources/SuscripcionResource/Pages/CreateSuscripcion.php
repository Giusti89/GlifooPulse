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
        $suscripcion = $this->record; // Ya es la suscripción

        $paquete = $suscripcion->paquete; // Accede a la relación paquete
        if ($paquete && $paquete->landing) {
            $tipoLanding = $paquete->landing->id;
        

        } else {
            $tipoLanding = 'default';
        }
        
        Spot::create([
            'suscripcion_id' => $suscripcion->id, 
            'tipolanding' => $tipoLanding,
            'estado' => 0,
        ]);
    }
}
