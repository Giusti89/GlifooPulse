<?php

namespace App\Filament\Widgets;

use App\Models\Plataforma_visit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\PlatformVisit;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getCards(): array
    {
        $today = now()->toDateString();

        return [
            Card::make(
                'Visitas hoy',
                Plataforma_visit::whereDate('created_at', $today)->count()
            ),

            Card::make(
                'Usuarios únicos hoy',
                Plataforma_visit::whereDate('created_at', $today)
                    ->distinct('session_id')
                    ->count('session_id')
            ),

            Card::make(
                'Visitas totales',
                Plataforma_visit::count()
            ),
        ];
    }
}
