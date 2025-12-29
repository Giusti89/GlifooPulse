<?php

namespace App\Filament\Portfolio\Widgets;

use App\Models\ConsultaProducto;
use App\Models\Portfolio;
use App\Models\Producto;
use App\Models\Spot;
use App\Models\Suscripcion;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class VisitasPorSpotChart extends BaseWidget
{
    protected function getStats(): array
    {

        $user = Auth::user();
        $suscripcion = Suscripcion::where('user_id', $user->id)->first();

        [$tiempoRestante, $descripcionTiempo, $colorSuscripcion] = $this->calcularTiempoSuscripcion($suscripcion);

        $spots = Spot::with('socials')
            ->whereHas('suscripcion', fn($q) => $q->where('user_id', $user->id))
            ->get();

        $totalVisitas   = $spots->sum('contador');
        $socialsList    = $spots->flatMap->socials;
        $totalRedes     = $socialsList->count();
        $topSocials     = $socialsList
            ->sortByDesc('clicks'); 

        $totalProductos = Portfolio::whereHas('spot.suscripcion', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $consultasQuery = ConsultaProducto::whereHas('producto.categoria.spot.suscripcion', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        $stats = [];

        // Suscripción
        $stats[] = Stat::make('Tiempo de suscripción', $tiempoRestante)
            ->description($descripcionTiempo)
            ->descriptionIcon('heroicon-m-clock')
            ->icon('heroicon-o-calendar')
            ->color($colorSuscripcion);

        // Visitas
        $stats[] = Stat::make('Visitas totales', number_format($totalVisitas))
            ->description('Total de visitas a tu página')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->icon('heroicon-o-eye')
            ->color('success');

        // Total de redes sociales configuradas
        $stats[] = Stat::make('Redes sociales', number_format($totalRedes))
            ->description($totalRedes
                ? 'Redes configuradas'
                : 'No tiene redes configuradas')
            ->icon('heroicon-o-share')
            ->color($totalRedes ? 'primary' : 'gray');

        $stats[] = Stat::make('Productos publicados', number_format($totalProductos))
            ->icon('heroicon-o-cube')
            ->color('info')
            ->description('Total de productos en tu catálogo');       

        // Top 3 redes por clics
        foreach ($topSocials as $social) {
            $stats[] = Stat::make($social->nombre, number_format($social->clicks))
                ->description('visitas')
                ->descriptionIcon('heroicon-m-user')
                ->icon('heroicon-o-arrow-trending-up')
                ->color('info');
        }

        return $stats;
    }
    protected function calcularTiempoSuscripcion(?Suscripcion $suscripcion): array
    {
        if (! $suscripcion || ! $suscripcion->fecha_fin) {
            return ['Sin datos', 'Información no disponible', 'gray'];
        }

        $hoy = Carbon::now()->startOfDay();
        $fin = Carbon::parse($suscripcion->fecha_fin)->startOfDay();

        if ($fin->isPast()) {
            return ['Expirada', 'La suscripción ya terminó', 'danger'];
        }

        $diff = $hoy->diff($fin);
        $meses = $diff->m + ($diff->y * 12);
        $dias  = $diff->d;
        $label = "{$meses} mes(es) y {$dias} día(s)";
        $desc  = "Restan {$label} de suscripción";

        return [$label, $desc, 'warning'];
    }
}
