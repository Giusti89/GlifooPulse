<?php

namespace App\Services;

use App\Filament\Catalogo\Pages\Bienvenida;
use App\Filament\Catalogo\Pages\Dashboard;
use App\Filament\Catalogo\Resources\CategoriaResource;
use App\Models\Categoria;
use App\Models\Producto;
use Filament\Pages\Dashboard as PagesDashboard;

class UserRedirectService
{
    public static function getRedirectUrl($user): string
    {
        // Si el usuario no existe, redirige al login
        if (!$user) {
            return route('login');
        }

        // Verifica si el usuario ya tiene categorías y productos
        $hasCategorias = Categoria::whereHas('spot.suscripcion', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();

        $hasProductos = Producto::whereHas('categoria.spot.suscripcion', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();

        // Si no tiene categorías o productos → Bienvenida
        if (!$hasCategorias || !$hasProductos) {
            return Bienvenida::getUrl(panel: 'catalogo');
        }

        // Caso contrario → Dashboard del catálogo
        return CategoriaResource::getUrl(panel: 'catalogo');
    }
}
