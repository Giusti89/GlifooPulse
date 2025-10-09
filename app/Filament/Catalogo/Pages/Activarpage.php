<?php

namespace App\Filament\Catalogo\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Activarpage extends Page
{
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
