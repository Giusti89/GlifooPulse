<?php

namespace App\Filament\Portfolio\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Activarpagina extends Page
{
    public function getTitle(): string | Htmlable
    {
        return __('Configuración final');
    }
    protected static ?string $navigationIcon = 'heroicon-s-play-circle';
    

    protected static string $view = 'filament.portfolio.pages.activarpagina';

    protected static ?string $navigationGroup = 'Publicación pagina web';
    protected static ?string $pluralModelLabel = 'Configuracion Enlaces';
    protected static ?string $navigationLabel = 'Configuración final';

    protected static ?int $navigationSort = 6;
}
