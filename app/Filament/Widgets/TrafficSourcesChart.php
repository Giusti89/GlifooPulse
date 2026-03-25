<?php

namespace App\Filament\Widgets;

use App\Models\Plataforma_visit;
use Filament\Widgets\ChartWidget;

class TrafficSourcesChart extends ChartWidget
{
    protected static ?string $heading = 'Fuentes de tráfico';
    protected static ?int $sort = 7;


    protected function getData(): array
    {
        $data = Plataforma_visit::all()
            ->groupBy(function ($visit) {
                return $this->detectSource($visit->referer);
            })
            ->map(fn($group) => $group->count())
            ->sortDesc();

        return [
            'datasets' => [
                [
                    'data' => $data->values(),
                ],
            ],
            'labels' => $data->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    private function detectSource($referer)
    {
        if (!$referer) return 'Directo';

        $referer = strtolower($referer);

        return match (true) {
            str_contains($referer, 'google') => 'Google',
            str_contains($referer, 'facebook') => 'Facebook',
            str_contains($referer, 'instagram') => 'Instagram',
            str_contains($referer, 'tiktok') => 'TikTok',
            str_contains($referer, 't.co') => 'Twitter',
            str_contains($referer, 'bing') => 'Bing',
            str_contains($referer, 'youtube') => 'YouTube',
            str_contains($referer, 'glifoo.org') => 'Interno',
            default => 'Otros',
        };
    }
}
