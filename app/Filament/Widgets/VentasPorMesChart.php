<?php

namespace App\Filament\Widgets;

use App\Models\Sell;
use App\Models\Venta;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;

class VentasPorMesChart extends ChartWidget
{
    protected static ?string $heading = 'Ingresos mensuales';
    protected static ?int $sort = 3;

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('fecha_inicio')
                ->label('Fecha inicio')
                ->default(now()->startOfMonth()),
            DatePicker::make('fecha_fin')
                ->label('Fecha fin')
                ->default(now()->endOfMonth()),
        ];
    }

    // Definimos los filtros que aparecerán arriba del widget
    protected function getFilters(): ?array
    {
        return [
            'thisMonth' => 'Este mes',
            'lastYear' => 'Año pasado',
            'year' => 'Este año',
        ];
    }

    protected function getData(): array
    {
        $query = Sell::query()
            ->where('estadov_id', 2); // ✅ solo ventas realizadas

        // Aplicar filtros dinámicos
        switch ($this->filter) {
            case 'thisMonth':
                $query->whereMonth('fecha', now()->month)
                    ->whereYear('fecha', now()->year);
                break;

            case 'lastYear':
                $query->whereYear('fecha', now()->subYear()->year);
                break;

            case 'year':
                $query->whereYear('fecha', now()->year);
                break;

            case 'custom':
                // Aquí puedes usar dos DatePickers en el widget para rango personalizado
                // Ejemplo: $this->form->getState()['fecha_inicio'], ['fecha_fin']
                // y aplicarlos en el query
                break;
        }

        $data = $query->selectRaw('MONTH(fecha) as mes, SUM(total) as total')
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
            'labels' => $data->pluck('mes')->map(fn($m) => Carbon::create()->month($m)->translatedFormat('F')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
