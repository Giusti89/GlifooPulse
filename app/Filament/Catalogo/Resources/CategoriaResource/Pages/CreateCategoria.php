<?php

namespace App\Filament\Catalogo\Resources\CategoriaResource\Pages;

use App\Filament\Catalogo\Resources\CategoriaResource;
use App\Models\Categoria;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class CreateCategoria extends CreateRecord
{
    protected static string $resource = CategoriaResource::class;


    public static function mutateFormDataBeforeSave(array $data): array
    {
        $data['spot_id'] = auth()->user()->spot_id;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function beforeCreate(): void
    {
        $user = Auth::user();
        $suscripcion = $user->getSuscripcionActiva();

        if (!$suscripcion || $suscripcion->estado != 1) {
            Notification::make()
                ->title('Suscripción requerida')
                ->body('Necesitas una suscripción activa para crear categorías.')
                ->danger()
                ->send();
            $this->halt();
            return;
        }

        // Contar categorías del usuario
        $categoriasCount = Categoria::whereHas('spot.suscripcion', fn($q) => $q->where('user_id', $user->id))->count();

        $maxCategorias = $suscripcion->paquete?->max_categorias;

        // Si no hay límite definido, permitir creación
        if ($maxCategorias === null) {
            return;
        }

        if ($categoriasCount >= $maxCategorias) {

            Notification::make()
                ->title('Límite alcanzado')
                ->body("Plan: {$suscripcion->paquete?->nombre} - Límite: {$maxCategorias} categorías")
                ->danger()
                ->persistent()
                ->actions([
                    Action::make('regresar')
                        ->label('Regresar') 
                        ->button() 
                        ->color('primary')  
                        ->url(route('filament.catalogo.resources.categorias.index'))
                ])
                ->send();

            $this->halt();
        }
    }
}
