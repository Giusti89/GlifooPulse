<?php

namespace App\Filament\Usuario\Widgets;

use App\Models\Social;
use App\Models\Spot;
use App\Models\Visit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;



class Estadisticas extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $suscripcion = \App\Models\Suscripcion::where('user_id', $user->id)->first();

        $tiempoRestante = null;

        if ($suscripcion && $suscripcion->fecha_fin) {
            $hoy = Carbon::now();
            $fin = Carbon::parse($suscripcion->fecha_fin);

            if ($fin->isPast()) {
                $tiempoRestante = 'Expirada';
            } else {
                $diasRestantes = $hoy->diffInDays($fin);
                $mesesRestantes = $hoy->diffInMonths($fin);

                $tiempoRestante = $mesesRestantes > 0
                    ? "$mesesRestantes mes(es) restantes"
                    : "$diasRestantes día(s) restantes";
            }
        }
        // Obtener los spots con sus relaciones
        $spots = Spot::with(['socials', 'visits'])
            ->whereHas('suscripcion', fn($q) => $q->where('user_id', $user->id))
            ->get();

        if ($spots->isEmpty()) {
            return [
                Card::make('Visitas totales', 0),
                Card::make('Visitas este mes', 0),
                Card::make('Redes sociales', 'No tiene redes configuradas'),
            ];
        }

        // Calcular métricas
        $stats = [
            'total_visits' => $spots->sum(fn($spot) => $spot->visits->count()),
            'monthly_visits' => $spots->sum(fn($spot) => $spot->visits
                ->where('visited_at', '>=', now()->startOfMonth())
                ->count()),
            'socials' => $spots->flatMap->socials
        ];

        // Cards base
        $cards = [
            Card::make('Visitas totales', $stats['total_visits'])
                ->icon('heroicon-s-users'),
            Card::make('Visitas este mes', $stats['monthly_visits'])
                ->icon('heroicon-s-calendar'),
            Card::make('Tiempo de suscripción', $tiempoRestante ?? 'No disponible')
                ->icon('heroicon-s-clock'),
        ];

        // Cards para redes sociales
        if ($stats['socials']->isEmpty()) {
            $cards[] = Card::make('Redes sociales', 'No tiene redes configuradas')
                ->icon('heroicon-s-exclamation-circle');
        } else {
            foreach ($stats['socials'] as $social) {
                $cards[] = Card::make($social->nombre, $social->clicks . ' vistas');
            }
        }

        return $cards;
    }
}
