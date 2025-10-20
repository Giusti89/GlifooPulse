<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Suscripcion;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SuscripcionesStats extends BaseWidget
{
    protected static ?int $sort = 1;
    

    protected function getStats(): array
    {
        $total = Suscripcion::count();
        $activas = Suscripcion::where('estado', true)->count();
        $expiradas = Suscripcion::where('estado', false)->count();

        return [
            Stat::make('Suscripciones activas', $activas)
                ->description('Actualmente en uso')
                ->color('success'),

            Stat::make('Suscripciones expiradas', $expiradas)
                ->description('Necesitan renovación')
                ->color('danger'),

            Stat::make('Total Suscripciones', $total)
                ->description('Registradas en el sistema')
                ->color('primary'),
        ];
    }
}
