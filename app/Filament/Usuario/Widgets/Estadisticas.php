<?php

namespace App\Filament\Usuario\Widgets;

use App\Models\Social;
use App\Models\Spot;
use App\Models\Suscripcion;
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

        $spot = Spot::with('suscripcion')
            ->whereHas('suscripcion', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->firstOrFail();

        $suscripcion = Suscripcion::where('user_id', $user->id)->first();
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
        // Obtener los spots con el nuevo contador
        $spots = Spot::with(['socials'])
            ->whereHas('suscripcion', fn($q) => $q->where('user_id', $user->id))
            ->get();

        // $urlCompleta = $spot ? url($spot->slug) : 'No configurada';

        if ($spots->isEmpty()) {
            return [
                Card::make('Visitas totales', 0)
                    ->icon('heroicon-s-users'),

                Card::make('Redes sociales', 'No tiene redes configuradas')
                    ->icon('heroicon-s-exclamation-circle'),

                Card::make('Tiempo de suscripción', $tiempoRestante ?? 'No disponible')
                    ->icon('heroicon-s-clock'),
            ];
        }
        // Calcular métricas
        // Calcular métricas simplificadas
        $totalVisits = $spots->sum('contador');
        $socials = $spots->flatMap->socials;

        // Cards base
        $cards = [
            Card::make('Visitas totales', number_format($totalVisits))
                ->icon('heroicon-s-users')
                ->color('success'),

            Card::make('Tiempo de suscripción', $tiempoRestante ?? 'No disponible')
                ->icon('heroicon-s-clock')
                ->color($tiempoRestante === 'Expirada' ? 'danger' : 'warning'),

            // Card::make('Tu página web', $urlCompleta)
            //     ->icon('heroicon-s-globe-alt')
            //     ->color('primary')
            //     ->extraAttributes([
            //         'class' => 'break-all whitespace-normal overflow-visible',
            //         'style' => 'word-break: break-all; line-height: 1.0;'
            //     ]),
        ];

        // Cards para redes sociales
        if ($socials->isEmpty()) {
            $cards[] = Card::make('Redes sociales', 'No tiene redes configuradas')
                ->icon('heroicon-s-exclamation-circle')
                ->color('gray');
        } else {
            // Agregar card con el total de redes sociales
            $cards[] = Card::make('Total redes sociales', number_format($socials->count()))
                ->icon('heroicon-s-share')
                ->color('primary');

            // Opcional: agregar las redes individuales si son pocas

            foreach ($socials as $social) {
                $cards[] = Card::make($social->nombre, number_format($social->clicks) . ' visitas')
                    ->icon('heroicon-s-arrow-trending-up')
                    ->color('info');
            }
        }
        return $cards;
    }
}
