<?php

namespace App\Filament\Widgets;

use App\Models\Social;
use App\Models\Spot;
use App\Models\User;
use Filament\Widgets\ChartWidget;

class SocialperUserChart extends ChartWidget
{
    protected static ?string $heading = 'Cantidad de redes sociales por cliente';
    protected static ?string $maxHeight = '300px';
    protected static ?int $sort = 2;


    protected function getData(): array
    {
        $data = Spot::withCount('socials')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Redes Sociales',
                    'data' => $data->pluck('socials_count'),
                    'backgroundColor' => '#60a5fa',
                ],
            ],
            'labels' => $data->pluck('titulo'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
