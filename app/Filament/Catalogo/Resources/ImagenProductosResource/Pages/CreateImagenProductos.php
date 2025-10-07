<?php

namespace App\Filament\Catalogo\Resources\ImagenProductosResource\Pages;

use App\Filament\Catalogo\Resources\ImagenProductosResource;
use App\Models\ImagenProducto;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\Facades\Storage;

class CreateImagenProductos extends CreateRecord
{
    protected static string $resource = ImagenProductosResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function beforeCreate(): void
    {
        $user = Auth::user();
        $suscripcion = $user->getSuscripcionActiva();

        // 1. Validación de suscripción
        if (! $suscripcion || $suscripcion->estado != 1) {
            Notification::make()
                ->title('Suscripción requerida')
                ->body('Necesitas una suscripción activa para subir imágenes.')
                ->danger()
                ->send();

            $this->halt();
            return;
        }

        // 2. ID de producto elegido
        $state       = $this->form->getState();
        $productoId  = $state['producto_id'] ?? null;
        $filePath    = $state['url'] ?? null; // Ruta relativa generada por FileUpload

        if (! $productoId) {
            // Si no hay producto, también borramos la imagen subida
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            Notification::make()
                ->title('Producto faltante')
                ->body('Selecciona primero un producto.')
                ->danger()
                ->send();

            $this->halt();
            return;
        }

        // 3. Conteo actual de imágenes para ese producto
        $imagenesCount = ImagenProducto::where('producto_id', $productoId)->count();
        $maxImagenes   = $suscripcion->paquete?->max_imagenes_producto;

        // 4. Si no hay límite, dejamos pasar
        if ($maxImagenes === null) {
            return;
        }

        // 5. Si se excede el límite, borramos el archivo y abortamos
        if ($imagenesCount >= $maxImagenes) {
            // Elimina físicamente el archivo que FileUpload ya puso en disco
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            Notification::make()
                ->title('Límite de imágenes alcanzado')
                ->body("Plan: {$suscripcion->paquete?->nombre} — Máximo: {$maxImagenes} imágenes por producto.")
                ->danger()
                ->persistent()
                ->actions([
                    Action::make('volver')
                        ->label('Volver al listado')
                        ->url(route('filament.catalogo.resources.imagen-productos.index'))
                        ->button()
                        ->color('primary'),
                ])
                ->send();

            $this->halt();
        }
    }
}
