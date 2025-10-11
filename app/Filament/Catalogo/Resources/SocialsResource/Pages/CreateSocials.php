<?php

namespace App\Filament\Catalogo\Resources\SocialsResource\Pages;

use App\Filament\Catalogo\Resources\SocialsResource;
use App\Models\Spot;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CreateSocials extends CreateRecord
{
    protected static string $resource = SocialsResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        // 2) Encontrar la suscripción activa
        $suscripcion = $user->getSuscripcionActiva();
        if (! $suscripcion) {
            Notification::make()
                ->title('No tienes suscripción activa')
                ->danger()
                ->send();
            $this->halt(); // detiene el guardado
        }

        $spot = Spot::where('suscripcion_id', $suscripcion->id)->first();
        if (! $spot) {
            Notification::make()
                ->title('No se encontró proyecto asociado')
                ->danger()
                ->send();
            $this->halt();
        }

      
        $data['spot_id'] = $spot->id;

        return $data;
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
                                ->url(route('filament.catalogo.resources.socials.index'))
                        ])
                        ->send();

                    $this->halt();
                }
            }
        }
    }
}
