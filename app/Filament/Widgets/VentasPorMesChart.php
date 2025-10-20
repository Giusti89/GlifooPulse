<?php

namespace App\Filament\Widgets;

use App\Models\Sell;
use App\Models\Venta;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class VentasPorMesChart extends ChartWidget
{
    protected static ?string $heading = 'Ingresos mensuales';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = Sell::selectRaw('MONTH(fecha) as mes, SUM(total) as total')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total de ventas',
                    'data' => $data->pluck('total'),
                    'borderColor' => '#f59e0b',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $data->pluck('mes')->map(fn ($m) => Carbon::create()->month($m)->translatedFormat('F')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}