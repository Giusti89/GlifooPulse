<?php

namespace App\Filament\Catalogo\Widgets;

use App\Models\Categoria;
use App\Models\ConsultaProducto;
use App\Models\Producto;
use App\Models\Spot;
use App\Models\Suscripcion;
use App\Models\VisitDaily;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Widget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Estadisticas extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        $user = Auth::user();
        if (! $user) return false;

        return Categoria::whereHas('spot.suscripcion', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();
    }

    protected function getStats(): array
    {
        $user = Auth::user();
        $suscripcion = Suscripcion::where('user_id', $user->id)->first();

        [$tiempoRestante, $descripcionTiempo, $colorSuscripcion] = $this->calcularTiempoSuscripcion($suscripcion);

        $spot = Spot::with('socials')
            ->whereHas('suscripcion', fn($q) => $q->where('user_id', $user->id))
            ->first();

        if (! $spot) {
            return [
                Stat::make('Sin datos', '—')
                    ->description('No hay spot configurado')
                    ->color('gray'),
            ];
        }

        // ============ VISITAS ============
        $visitas30 = VisitDaily::where('spot_id', $spot->id)
            ->where('date', '>=', now()->subDays(30)->toDateString())
            ->sum('visits');

        $visitas30Prev = VisitDaily::where('spot_id', $spot->id)
            ->whereBetween('date', [
                now()->subDays(60)->toDateString(),
                now()->subDays(31)->toDateString(),
            ])
            ->sum('visits');

        $cambio = $visitas30Prev > 0
            ? round((($visitas30 - $visitas30Prev) / $visitas30Prev) * 100, 1)
            : ($visitas30 > 0 ? 100 : 0);

        $colorCambio = $cambio >= 0 ? 'success' : 'danger';
        $iconoCambio = $cambio >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $textoCambio = $visitas30Prev > 0
            ? ($cambio >= 0 ? "+{$cambio}% vs 30d anteriores" : "{$cambio}% vs 30d anteriores")
            : 'Sin período anterior para comparar';

        // ============ CONSULTAS ============
        $consultasQuery = ConsultaProducto::whereHas('producto.categoria.spot.suscripcion', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        $totalConsultas = $consultasQuery->count();

        $consultas7d = (clone $consultasQuery)
            ->where('fecha_consulta', '>=', now()->subDays(7))
            ->count();

        // 👇 CORRECCIÓN: solo contar consultas desde que existe tracking
        // (primera fecha con datos en visit_dailies)
        $fechaInicioTracking = VisitDaily::where('spot_id', $spot->id)
            ->min('date');

        $consultas30dQuery = (clone $consultasQuery)
            ->where('fecha_consulta', '>=', now()->subDays(30));

        if ($fechaInicioTracking) {
            $consultas30dQuery->where('fecha_consulta', '>=', $fechaInicioTracking);
        }

        $consultas30d = $consultas30dQuery->count();

        // ============ TASA DE CONVERSIÓN (30d) ============
        // Solo calcular si hay tracking activo con al menos 7 días de datos
        $diasTracking = $fechaInicioTracking
            ? Carbon::parse($fechaInicioTracking)->diffInDays(now())
            : 0;

        $puedeCalcularConversion = $diasTracking >= 7 && $visitas30 >= 10;

        $conversion = $puedeCalcularConversion && $visitas30 > 0
            ? round(($consultas30d / $visitas30) * 100, 2)
            : null;

        $colorConversion = $conversion === null
            ? 'gray'
            : ($conversion >= 3 ? 'success' : ($conversion >= 1 ? 'warning' : 'gray'));

        $valorConversion = $conversion === null ? '—' : "{$conversion}%";

        $descripcionConversion = $conversion === null
            ? 'Se calculará cuando haya más datos'
            : "{$consultas30d} consultas / {$visitas30} visitas";

        // ============ FUENTE TOP DE TRÁFICO (30d) ============
        $fuenteTop = VisitDaily::where('spot_id', $spot->id)
            ->where('date', '>=', now()->subDays(30)->toDateString())
            ->whereNotNull('utm_source')
            ->selectRaw('utm_source, SUM(visits) as total')
            ->groupBy('utm_source')
            ->orderByDesc('total')
            ->first();

        $fuenteTopNombre = $fuenteTop?->utm_source ?? 'Tráfico directo';
        $fuenteTopVisitas = $fuenteTop?->total ?? 0;

        // Si no hay UTM pero sí hay referrer, usarlo como fuente
        if (! $fuenteTop) {
            $referrerTop = VisitDaily::where('spot_id', $spot->id)
                ->where('date', '>=', now()->subDays(30)->toDateString())
                ->whereNotNull('referrer_domain')
                ->selectRaw('referrer_domain, SUM(visits) as total')
                ->groupBy('referrer_domain')
                ->orderByDesc('total')
                ->first();

            if ($referrerTop) {
                $fuenteTopNombre = $referrerTop->referrer_domain;
                $fuenteTopVisitas = $referrerTop->total;
            }
        }

        // ============ PRODUCTO MÁS CONSULTADO ============
        $productoTop = (clone $consultasQuery)
            ->selectRaw('producto_id, COUNT(*) as total')
            ->groupBy('producto_id')
            ->orderByDesc('total')
            ->with('producto:id,nombre')
            ->first();

        $nombreTopProducto = $productoTop?->producto?->nombre ?? 'Sin consultas';
        $totalTopProducto = $productoTop?->total ?? 0;

        // ============ PRODUCTOS TOTALES ============
        $totalProductos = Producto::whereHas('categoria.spot.suscripcion', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        // ============ REDES SOCIALES ============
        $socialsList = $spot->socials;
        $topSocials = $socialsList->sortByDesc('clicks');

        // ============ CONSTRUIR STATS ============
        $stats = [];

        // 1. Suscripción
        $stats[] = Stat::make('Tiempo de suscripción', $tiempoRestante)
            ->description($descripcionTiempo)
            ->descriptionIcon('heroicon-m-clock')
            ->icon('heroicon-o-calendar')
            ->color($colorSuscripcion);

        // 2. Visitas últimos 30 días
        $stats[] = Stat::make('Visitas últimos 30 días', number_format($visitas30))
            ->description($textoCambio)
            ->descriptionIcon($iconoCambio)
            ->icon('heroicon-o-eye')
            ->color($colorCambio);

        // 3. Visitas totales históricas
        $stats[] = Stat::make('Visitas totales', number_format($spot->contador))
            ->description('Desde el inicio del catálogo')
            ->descriptionIcon('heroicon-m-chart-bar')
            ->icon('heroicon-o-globe-alt')
            ->color('primary');

        // 4. Fuente top de tráfico
        $stats[] = Stat::make('Fuente principal', ucfirst($fuenteTopNombre))
            ->description("{$fuenteTopVisitas} visitas en 30 días")
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->icon('heroicon-o-megaphone')
            ->color('info');

        // 5. Tasa de conversión
        $stats[] = Stat::make('Tasa de conversión (30d)', $valorConversion)
            ->description($descripcionConversion)
            ->descriptionIcon('heroicon-m-calculator')
            ->icon('heroicon-o-arrow-trending-up')
            ->color($colorConversion);

        // 6. Consultas últimos 7 días
        $stats[] = Stat::make('Consultas (últimos 7 días)', number_format($consultas7d))
            ->description('Interés reciente en tus productos')
            ->descriptionIcon('heroicon-m-calendar-days')
            ->icon('heroicon-o-calendar-days')
            ->color($consultas7d > 0 ? 'warning' : 'gray');

        // 7. Más consultado
        $stats[] = Stat::make('Más consultado', $nombreTopProducto)
            ->description("{$totalTopProducto} consultas")
            ->descriptionIcon('heroicon-m-fire')
            ->icon('heroicon-o-fire')
            ->color($totalTopProducto > 0 ? 'info' : 'gray');

        // 8. Productos publicados
        $stats[] = Stat::make('Productos publicados', number_format($totalProductos))
            ->description('Total en tu catálogo')
            ->descriptionIcon('heroicon-m-cube')
            ->icon('heroicon-o-cube')
            ->color('info');

        // 9. Top 3 redes por clicks
        foreach ($topSocials as $social) {
            $stats[] = Stat::make($social->nombre, number_format($social->clicks))
                ->description('clicks totales')
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
        $dias = $diff->d;

        $label = "{$meses} mes(es) y {$dias} día(s)";
        $desc = "Restan {$label} de suscripción";

        return [$label, $desc, 'warning'];
    }
}
