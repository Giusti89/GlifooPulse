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
                $descripcionTiempo = 'La suscripción ya terminó';
            } else {
                $diff = $hoy->diff($fin);
                $mesesRestantes = $diff->m + ($diff->y * 12);
                $diasRestantes = $diff->d;
                $tiempoRestante = "{$mesesRestantes} mes(es) y {$diasRestantes} día(s)";
                $descripcionTiempo = "Restan {$mesesRestantes} mes(es) y {$diasRestantes} día(s) de suscripción";
            }
        }
        // Obtener los spots con el nuevo contador
        $spots = Spot::with(['socials'])
            ->whereHas('suscripcion', fn($q) => $q->where('user_id', $user->id))
            ->get();

        // $urlCompleta = $spot ? url($spot->slug) : 'No configurada';

        if ($spots->isEmpty()) {
            return [
                Stat::make('Visitas totales', 0)
                    ->icon('heroicon-s-users'),

                Stat::make('Redes sociales', 'No tiene redes configuradas')
                    ->icon('heroicon-s-exclamation-circle'),

                Stat::make('Tiempo de suscripción', $tiempoRestante ?? 'Sin datos')
                    ->description($descripcionTiempo ?? '')
                    ->descriptionIcon('heroicon-m-clock')
                    ->icon('heroicon-o-calendar')
                    ->color(($tiempoRestante ?? '') === 'Expirada' ? 'danger' : 'success'),
            ];
        }
        // Calcular métricas
        // Calcular métricas simplificadas
        $totalVisits = $spots->sum('contador');
        $socials = $spots->flatMap->socials;

        // Cards base
        $cards = [
            Stat::make('Visitas totales', number_format($totalVisits))
                ->icon('heroicon-s-users')
                ->color('success'),

            Stat::make('Tiempo de suscripción', $tiempoRestante ?? 'No disponible')
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
