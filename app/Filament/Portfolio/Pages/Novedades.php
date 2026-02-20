<?php

namespace App\Filament\Portfolio\Pages;

use Filament\Pages\Page;

class Novedades extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.portfolio.pages.novedades';
    protected static ?string $navigationGroup = 'Novedades';
    protected static ?string $navigationLabel = 'Novedades del sistema';
    protected static ?string $pluralModelLabel = 'Configuracion Enlaces';
    protected static ?int $navigationSort = 7;
}
