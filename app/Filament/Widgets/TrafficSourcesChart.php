<?php

namespace App\Filament\Widgets;

use App\Models\Plataforma_visit;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TrafficSourcesChart extends ChartWidget
{
    protected static ?string $heading = 'Fuentes de tráfico';
    protected static ?int $sort = 7;

    protected function getData(): array
    {
        $visits = Plataforma_visit::query()
            ->when(!app()->runningInConsole(), function ($query) {
                // Filtrar visitas de los últimos 30 días por defecto
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
            })
            ->get();

        if ($visits->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'data' => [1],
                        'backgroundColor' => ['#e2e8f0'],
                    ],
                ],
                'labels' => ['Sin datos'],
            ];
        }

        $grouped = $visits->groupBy(fn($visit) => $this->detectSource($visit->referer));

        $data = $grouped->map->count()->sortDesc();
        $total = $data->sum();

        return [
            'datasets' => [
                [
                    'data' => $data->values(),
                    'backgroundColor' => [
                        '#10B981',
                        '#3B82F6',
                        '#4267B2',
                        '#E1306C',
                        '#000000',
                        '#1DA1F2',
                        '#FF0000',
                        '#94A3B8',
                    ],
                ],
            ],
            'labels' => $data->keys()->map(function ($label) use ($data, $total) {
                $percentage = round(($data[$label] / $total) * 100, 1);
                return "{$label} ({$percentage}%)";
            }),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    private function detectSource($referer)
    {
        if (empty($referer)) {
            return 'Directo';
        }

        $referer = strtolower($referer);

        $sources = [
            'Google' => ['google', 'googlebot'],
            'Facebook' => ['facebook', 'fb.com'],
            'Instagram' => ['instagram'],
            'TikTok' => ['tiktok'],
            'Twitter' => ['twitter', 't.co'],
            'YouTube' => ['youtube', 'youtu.be'],
            'Bing' => ['bing'],
            'Interno' => ['glifoo.org', request()->getHost()],
        ];

        foreach ($sources as $source => $patterns) {
            foreach ($patterns as $pattern) {
                if (str_contains($referer, $pattern)) {
                    return $source;
                }
            }
        }

        return 'Otros';
    }
}
