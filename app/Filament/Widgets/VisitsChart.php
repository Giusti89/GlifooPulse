<?php

namespace App\Filament\Widgets;

use App\Models\Plataforma_visit;
use Filament\Widgets\ChartWidget;

class VisitsChart extends ChartWidget
{
    protected static ?string $heading = 'Visitas últimos 7 días';
    protected static ?int $sort = 3;


    protected function getData(): array
    {
        $visits = Plataforma_visit::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $labels = collect(range(0, 6))
            ->map(fn($i) => now()->subDays(6 - $i)->format('Y-m-d'));

        $data = $labels->map(fn($date) => $visits[$date] ?? 0);

        return [
            'datasets' => [
                [
                    'label' => 'Visitas',
                    'data' => $data,
                ],
            ],
            'labels' => $labels->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
