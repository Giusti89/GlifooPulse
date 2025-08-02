<?php

namespace App\Filament\Usuario\Pages;

use Filament\Pages\Page;

class Plantilla extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.usuario.pages.plantilla';
    protected static ?string $navigationGroup = 'Novedades';
    protected static ?string $navigationLabel = 'Tienda de plantillas';
    protected static ?string $pluralModelLabel = 'Configuracion Enlaces';
    protected static ?int $navigationSort = 4;


    
}
