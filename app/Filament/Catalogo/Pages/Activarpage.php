<?php

namespace App\Filament\Catalogo\Pages;

use App\Models\Categoria;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Activarpage extends Page
{
    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        // Verifica si el usuario tiene al menos una categoría a través de la relación
        return Categoria::whereHas('spot.suscripcion', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();
    }
    
    public function getTitle(): string | Htmlable
    {
        return __('Configuración final');
    }
    protected static ?string $navigationIcon = 'heroicon-s-play-circle';

    protected static string $view = 'filament.usuario.pages.activarpage';
    protected static ?string $navigationGroup = 'Configuracion pagina web';
    protected static ?string $pluralModelLabel = 'Configuracion Enlaces';
    protected static ?string $navigationLabel = 'Configuración final';

    protected static ?int $navigationSort = 6;
}
