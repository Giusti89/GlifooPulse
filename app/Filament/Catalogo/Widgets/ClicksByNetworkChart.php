<?php

namespace App\Filament\Catalogo\Widgets;

use App\Models\Categoria;
use App\Models\SocialClickDaily;
use App\Models\Spot;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;


class ClicksByNetworkChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Clicks en redes (últimos 30 días)';
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
        $spot = Spot::with('socials')
            ->whereHas('suscripcion', fn($q) => $q->where('user_id', $user->id))
            ->first();

        if (! $spot || $spot->socials->isEmpty()) {
            return [
                'datasets' => [['data' => [0], 'backgroundColor' => ['#9CA3AF']]],
                'labels' => ['Sin datos'],
            ];
        }

        $socialIds = $spot->socials->pluck('id');

        $rows = SocialClickDaily::whereIn('social_id', $socialIds)
            ->where('date', '>=', now()->subDays(30)->toDateString())
            ->selectRaw('social_id, SUM(clicks) as total')
            ->groupBy('social_id')
            ->get()
            ->keyBy('social_id');

        $labels = [];
        $data = [];
        $colores = [];

        foreach ($spot->socials as $social) {
            $labels[] = $social->nombre;
            $data[] = (int) ($rows->get($social->id)?->total ?? 0);
            $colores[] = $this->getColorForSocial($social->nombre);
        }

        return [
            'datasets' => [[
                'data' => $data,
                'backgroundColor' => $colores,
                'borderWidth' => 0,
                'borderRadius' => 6,
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
        return 250;
    }

    private function getColorForSocial(string $nombre): string
    {
        $nombreLimpio = strtolower(preg_replace('/\([^\)]+\)/', '', $nombre));
        $nombreLimpio = preg_replace('/[^a-z0-9]/', '', trim($nombreLimpio));

        $colores = [
            'facebook' => '#1877F2',
            'fb' => '#1877F2',
            'instagram' => '#E4405F',
            'ig' => '#E4405F',
            'twitter' => '#1DA1F2',
            'x' => '#000000',
            'youtube' => '#FF0000',
            'yt' => '#FF0000',
            'tiktok' => '#000000',
            'tk' => '#000000',
            'whatsapp' => '#25D366',
            'wa' => '#25D366',
            'linkedin' => '#0A66C2',
            'telegram' => '#26A5E4',
        ];

        return $colores[$nombreLimpio] ?? '#9CA3AF';
    }
}
