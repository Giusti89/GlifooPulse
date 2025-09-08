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
        $descripcionTiempo = '';

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

        if ($spots->isEmpty()) {
            return [
                Stat::make('Visitas totales', 0)
                    ->icon('heroicon-o-users')
                    ->color('gray')
                    ->description('No hay visitas registradas'),

                Stat::make('Redes sociales', '0 configuradas')
                    ->icon('heroicon-o-exclamation-circle')
                    ->color('gray')
                    ->description('No tiene redes configuradas'),

                Stat::make('Tiempo de suscripción', $tiempoRestante ?? 'Sin datos')
                    ->description($descripcionTiempo ?? 'Información no disponible')
                    ->descriptionIcon('heroicon-m-clock')
                    ->icon('heroicon-o-calendar')
                    ->color(($tiempoRestante ?? '') === 'Expirada' ? 'danger' : 'success'),
            ];
        }

        // Calcular métricas
        $totalVisits = $spots->sum('contador');
        $socials = $spots->flatMap->socials;

        // Cards base con Stat (no Card)
        $stats = [
            Stat::make('Visitas totales', number_format($totalVisits))
                ->icon('heroicon-o-eye')
                ->color('success')
                ->description('Total de visitas a tu página')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

            Stat::make('Tiempo de suscripción', $tiempoRestante ?? 'No disponible')
                ->description($descripcionTiempo)
                ->descriptionIcon('heroicon-m-clock')
                ->icon('heroicon-o-calendar')
                ->color($tiempoRestante === 'Expirada' ? 'danger' : 'warning'),
        ];

        // Stats para redes sociales
        if ($socials->isEmpty()) {
            $stats[] = Stat::make('Redes sociales', '0')
                ->icon('heroicon-o-share')
                ->color('gray')
                ->description('No tiene redes configuradas');
        } else {
            // Agregar stat con el total de redes sociales
            $stats[] = Stat::make('Total redes', number_format($socials->count()))
                ->icon('heroicon-o-share')
                ->color('primary')
                ->description('Redes sociales configuradas');

            // Agregar las redes individuales más importantes (máximo 3 para no saturar)
            $topSocials = $socials->sortByDesc('clicks')->take(3);
            
            foreach ($topSocials as $social) {
                $stats[] = Stat::make($social->nombre, number_format($social->clicks))
                    ->icon('heroicon-o-arrow-trending-up')
                    ->color('info')
                    ->description('visitas')
                    ->descriptionIcon('heroicon-m-user');
            }
        }

        return $stats;
    }
}
