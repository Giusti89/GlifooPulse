<?php

namespace App\Filament\Usuario\Resources\SocialResource\Pages;

use App\Filament\Usuario\Resources\SocialResource;
use App\Models\Social;
use App\Models\Spot;
use App\Models\Suscripcion;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSocial extends CreateRecord
{
    protected static string $resource = SocialResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function beforeCreate(): void
    {
        $user = Auth::user();

        // Obtener la suscripción activa (o null si no tiene)
        $suscripcion = $user->getSuscripcionActiva();
        $spots = Spot::with(['socials'])
            ->whereHas('suscripcion', fn($q) => $q->where('user_id', $user->id))
            ->get();

        $socials = $spots->flatMap->socials;


        if ($suscripcion->estado == 1) {
            $max = $suscripcion->paquete?->max_redes_sociales;

            if ($max !== null) {
                $count = number_format($socials->count());

                if ($count >= $max) {
                    Notification::make()
                        ->title('Has alcanzado el máximo de redes sociales permitidas en tu plan.')
                        ->danger()
                        ->persistent()
                        ->actions([
                            Action::make('renovar')
                                ->label('Regresar')  // Texto del botón
                                ->button()  // Estilo de botón
                                ->color('primary')  // Color (opcional)
                                ->url(route('filament.usuario.resources.socials.index'))  
                        ])
                        ->send();

                    $this->halt();
                }
            }
        }
    }
}
