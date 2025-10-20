<?php

namespace App\Filament\Widgets;

use App\Models\Spot;
use Filament\Widgets\ChartWidget;

class VisitasPorSpotChart extends ChartWidget
{
    protected static ?string $heading = 'Visitas por Cliente (contador)';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        // Obtener titulo y contador (asegurando entero y cero por defecto)
        $data = Spot::select('titulo', 'contador')
            ->orderBy('contador', 'desc')
            ->get();

        // Si necesitas evitar etiquetas muy largas, puedes limitar o truncar aquí
        $labels = $data->pluck('titulo')->map(fn($t) => strlen($t) > 25 ? substr($t, 0, 22) . '...' : $t);
        $values = $data->pluck('contador')->map(fn($c) => (int) $c);

        return [
            'datasets' => [
                [
                    'label' => 'Visitas (contador)',
                    'data' => $values,
                    // Filament/Chart.js permite varios campos; aquí un ejemplo
                    'backgroundColor' => '#a855f7',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
