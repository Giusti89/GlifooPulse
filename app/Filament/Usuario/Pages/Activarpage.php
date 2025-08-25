<?php

namespace App\Filament\Usuario\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Activarpage extends Page
{
    public function getTitle(): string | Htmlable
    {
        return __('Configuración final');
    }
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.usuario.pages.activarpage';
    protected static ?string $navigationGroup = 'Configuracion pagina web';
    protected static ?string $pluralModelLabel = 'Configuracion Enlaces';
    protected static ?string $navigationLabel = 'Configuración final';

    protected static ?int $navigationSort = 4;
}
