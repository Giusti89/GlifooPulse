<?php

namespace App\Filament\Usuario\Widgets;

use App\Models\Spot;
use App\Models\Visit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Database\Eloquent\Builder;


class Estadisticas extends BaseWidget
{

    protected function getStats(): array
    {
        $user = auth()->user();

        $spot = Spot::whereHas('suscripcion', function ($query) {
            $query->where('user_id', auth()->id());
        })->first();

        if (!$spot) {
            return [
                Card::make('Visitas totales', 0),
                Card::make('Visitantes únicos', 0),
                Card::make('Visitas este mes', 0),
            ];
        }

        $total = Visit::where('spot_id', $spot->id)->count();

        $unique = Visit::where('spot_id', $spot->id)
            ->select('ip')
            ->distinct()
            ->count();
        $monthly = Visit::where('spot_id', $spot->id)
            ->whereMonth('visited_at', now()->month)
            ->count();

        return [
            Card::make('Visitas totales', $total),
            Card::make('Visitantes únicos', $unique),
            Card::make('Visitas este mes', $monthly),
        ];
    }
}
