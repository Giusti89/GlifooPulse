<?php

namespace App\Filament\Catalogo\Pages;

use App\Models\Categoria;
use Filament\Pages\Page;

class Bienvenida extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static string $view = 'filament.catalogo.pages.bienvenida';
    protected static ?string $navigationLabel = 'Primeros pasos';
    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        // Verifica si el usuario tiene al menos una categoría a través de la relación
        return !Categoria::whereHas('spot.suscripcion', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();
    }
    
}
