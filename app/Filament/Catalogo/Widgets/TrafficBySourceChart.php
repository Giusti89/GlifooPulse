<?php

namespace App\Filament\Catalogo\Widgets;

use App\Models\Categoria;
use App\Models\Spot;
use App\Models\VisitDaily;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;


class TrafficBySourceChart extends ChartWidget
{
   protected static ?int $sort = 2;
    protected static ?string $heading = 'Tráfico por fuente (últimos 30 días)';
    protected static ?string $pollingInterval = '60s';
    

    public static function canView(): bool
    {
        $user = Auth::user();
        if (! $user) return false;

        return Categoria::whereHas('spot.suscripcion', fn($q) => $q->where('user_id', $user->id))->exists();
    }

    protected function getData(): array
    {
        $user = Auth::user();
        $spot = Spot::whereHas('suscripcion', fn($q) => $q->where('user_id', $user->id))->first();

        if (! $spot) {
            return [
                'datasets' => [['data' => [0], 'backgroundColor' => ['#9CA3AF']]],
                'labels' => ['Sin datos'],
            ];
        }

        $rows = VisitDaily::where('spot_id', $spot->id)
            ->where('date', '>=', now()->subDays(30)->toDateString())
            ->selectRaw('COALESCE(utm_source, referrer_domain, "Directo") as fuente, SUM(visits) as total')
            ->groupBy('fuente')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $labels = $rows->pluck('fuente')->map(fn($f) => ucfirst((string) $f))->toArray();
        $data = $rows->pluck('total')->toArray();

        $colores = [
            '#FF6B35', // TikTok-ish naranja
            '#E4405F', // Instagram
            '#25D366', // WhatsApp
            '#1877F2', // Facebook
            '#4285F4', // Google
            '#9CA3AF', // Directo
            '#8B5CF6', // Otros
        ];

        return [
            'datasets' => [[
                'data' => $data,
                'backgroundColor' => array_slice($colores, 0, count($data)),
                'borderWidth' => 2,
                'borderColor' => '#fff',
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'right',
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
