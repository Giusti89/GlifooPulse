<?php

namespace App\Filament\Catalogo\Resources\ProductosResource\Pages;

use App\Filament\Catalogo\Resources\ProductosResource;
use App\Models\Categoria;
use App\Models\Producto;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;



class CreateProductos extends CreateRecord
{
    protected static string $resource = ProductosResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function beforeCreate(): void
    {
        $categoriaId = $this->form->getState()['categoria_id'] ?? null;

        if (!$categoriaId) {
            return;
        }

        $categoria = Categoria::find($categoriaId);
        $suscripcion = Auth::user()->getSuscripcionActiva();
        $maxProductos = $suscripcion->paquete?->max_productos;

        if ($maxProductos !== null && $categoria->productos()->count() >= $maxProductos) {
            Notification::make()
                ->title('Límite de productos alcanzado')
                ->body("Plan: {$suscripcion->paquete?->nombre} - Límite: {$maxProductos} productos")
                ->danger()
                ->persistent()
                ->actions([
                    Action::make('regresar')
                        ->label('Regresar')  
                        ->button()  
                        ->color('primary')  
                        ->url(route('filament.catalogo.resources.productos.index'))
                ])
                ->send();

            $this->halt();
        }
    }
}
