<?php

namespace App\Filament\Catalogo\Widgets;

use App\Models\Categoria;
use App\Models\Spot;
use App\Models\VisitDaily;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class TrafficTrendChart extends ChartWidget
{
    protected static ?int $sort = 6;
    protected static ?string $pollingInterval = '60s';
    protected int|string|array $columnSpan = 2;

    public static function canView(): bool
    {
        $user = Auth::user();
        if (! $user) return false;

        return Categoria::whereHas('spot.suscripcion', fn($q) => $q->where('user_id', $user->id))->exists();
    }

    public function getHeading(): string
    {
        $dias = match ($this->filter) {
            '7' => 'últimos 7 días',
            '90' => 'últimos 90 días',
            default => 'últimos 30 días',
        };

        return "Evolución de visitas ({$dias})";
    }

    protected function getData(): array
    {
        $user = Auth::user();
        $spot = Spot::whereHas('suscripcion', fn($q) => $q->where('user_id', $user->id))->first();

        if (! $spot) {
            return [
                'datasets' => [['label' => 'Sin datos', 'data' => [0], 'borderColor' => '#9CA3AF']],
                'labels' => ['Sin datos'],
            ];
        }

        $dias = match ($this->filter) {
            '7' => 7,
            '90' => 90,
            default => 7,
        };

        $inicio = now()->subDays($dias - 1)->startOfDay();
        $fin = now()->endOfDay();

        $rows = VisitDaily::where('spot_id', $spot->id)
            ->whereBetween('date', [$inicio->toDateString(), $fin->toDateString()])
            ->selectRaw('date, SUM(visits) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy(fn($r) => Carbon::parse($r->date)->toDateString());

        $labels = [];
        $data = [];

        for ($i = $dias - 1; $i >= 0; $i--) {
            $fecha = now()->subDays($i)->toDateString();
            $labels[] = Carbon::parse($fecha)->format('d M');
            $data[] = $rows->get($fecha)?->total ?? 0;
        }

        return [
            'datasets' => [[
                'label' => 'Visitas',
                'data' => $data,
                'borderColor' => '#FF6B35',
                'backgroundColor' => '#FF6B3520',
                'tension' => 0.3,
                'fill' => true,
                'pointBackgroundColor' => '#FF6B35',
                'pointBorderColor' => '#fff',
                'pointHoverRadius' => 6,
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            '7' => 'Últimos 7 días',
            '30' => 'Últimos 30 días',
            '90' => 'Últimos 90 días',
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => ['stepSize' => 1, 'precision' => 0],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }

    protected function getHeight(): int
    {
        return 300;
    }
}
