<?php

namespace App\Filament\Resources\SuscripcionResource\Pages;

use App\Filament\Resources\SuscripcionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuscripcion extends EditRecord
{
    protected static string $resource = SuscripcionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function afterSave(): void
    {
        $suscripcion = $this->record;

        
        $spot = \App\Models\Spot::where('suscripcion_id', $suscripcion->id)->first();

        // Verificamos que exista un spot y que el paquete tenga una landing asociada
        if ($spot && $suscripcion->paquete && $suscripcion->paquete->landing) {
            $nuevoTipoLanding = $suscripcion->paquete->landing->id;

            // Actualizamos solo si cambió
            if ($spot->tipolanding != $nuevoTipoLanding) {
                $spot->tipolanding = $nuevoTipoLanding;
                $spot->save();
            }
        }
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
