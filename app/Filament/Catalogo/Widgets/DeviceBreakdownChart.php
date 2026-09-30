<?php

namespace App\Filament\Catalogo\Widgets;

use App\Models\Categoria;
use App\Models\Spot;
use App\Models\VisitDaily;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;


class DeviceBreakdownChart extends ChartWidget
{
     protected static ?int $sort = 5;
    protected static ?string $heading = 'Dispositivos (últimos 30 días)';
    protected static ?string $pollingInterval = '60s';
    protected int|string|array $columnSpan = 1;

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
            ->selectRaw('COALESCE(device_type, "desconocido") as device, SUM(visits) as total')
            ->groupBy('device')
            ->orderByDesc('total')
            ->get();

        $labels = $rows->pluck('device')->map(fn($d) => match ($d) {
            'mobile' => 'Móvil',
            'desktop' => 'Escritorio',
            'tablet' => 'Tablet',
            default => 'Desconocido',
        })->toArray();

        $data = $rows->pluck('total')->toArray();

        $colores = [
            '#FF6B35', // mobile
            '#4A90E2', // desktop
            '#9B59B6', // tablet
            '#9CA3AF', // desconocido
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
                    'position' => 'bottom',
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
