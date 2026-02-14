<?php

namespace App\Filament\Catalogo\Widgets;

use App\Models\SocialClicks;
use App\Models\Spot;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class ClicksChartC extends ChartWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Evolución de clicks por red social';

    protected static ?string $pollingInterval = '60s';

    protected static ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => true,
                'position' => 'top',
            ],
            'tooltip' => [
                'mode' => 'index',
                'intersect' => false,
            ],
        ],
        'scales' => [
            'y' => [
                'beginAtZero' => true,
                'ticks' => [
                    'stepSize' => 1,
                    'precision' => 0,
                ],
            ],
        ],
        'responsive' => true,
        'maintainAspectRatio' => false,
    ];

    protected function getData(): array
    {
        $user = auth()->user();

        // Obtener el spot del usuario
        $spot = Spot::with('suscripcion')
            ->whereHas('suscripcion', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->first();

        if (!$spot) {
            return [
                'datasets' => [
                    [
                        'label' => 'Sin datos',
                        'data' => [0],
                        'borderColor' => '#9CA3AF',
                    ]
                ],
                'labels' => ['Sin datos'],
            ];
        }

        // Determinar número de meses según filtro
        $mesesCount = match ($this->filter) {
            '12' => 11,
            '24' => 23,
            default => 5,
        };

        // Obtener los meses
        $meses = collect(range($mesesCount, 0))->map(function ($i) {
            return Carbon::now()->subMonths($i)->startOfMonth();
        });

        // Obtener las redes sociales del spot del usuario
        $redesSociales = $spot->socials()->get();

        if ($redesSociales->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'label' => 'Sin redes sociales',
                        'data' => array_fill(0, count($meses), 0),
                        'borderColor' => '#9CA3AF',
                        'borderDash' => [5, 5],
                    ]
                ],
                'labels' => $meses->map(fn($mes) => $mes->format('M Y'))->toArray(),
            ];
        }

        $datasets = [];

        // Paleta de colores para redes no reconocidas
        $paletaColores = [
            '#FF6B6B',
            '#4ECDC4',
            '#45B7D1',
            '#96CEB4',
            '#FFEAA7',
            '#DDA0DD',
            '#98D8C8',
            '#F7DC6F',
            '#BB8FCE',
            '#85C1E2',
            '#F1948A',
            '#82E0AA',
            '#F8C471',
            '#B2BABB',
            '#D7BDE2',
        ];

        $indiceColor = 0;

        foreach ($redesSociales as $red) {
            $datosPorMes = [];

            foreach ($meses as $mes) {
                $siguienteMes = $mes->copy()->addMonth();

                $totalClicks = SocialClicks::where('social_id', $red->id)
                    ->whereBetween('created_at', [$mes, $siguienteMes])
                    ->count();

                $datosPorMes[] = $totalClicks;
            }

            // Obtener color según el nombre de la red
            $color = $this->getColorForSocial($red->nombre);

            // Si no hay color asignado, usar uno de la paleta
            if (!$color) {
                $color = $paletaColores[$indiceColor % count($paletaColores)];
                $indiceColor++;
            }

            $datasets[] = [
                'label' => $red->nombre,
                'data' => $datosPorMes,
                'borderColor' => $color,
                'backgroundColor' => $color . '20',
                'tension' => 0.3,
                'fill' => false,
                'pointBackgroundColor' => $color,
                'pointBorderColor' => '#fff',
                'pointHoverRadius' => 6,
                'pointHoverBackgroundColor' => $color,
                'pointHoverBorderColor' => '#fff',
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $meses->map(fn($mes) => $mes->format('M Y'))->toArray(),
        ];
    }

    /**
     * Obtiene el color según el nombre de la red social
     * Soporta nombres con variaciones (mayúsculas/minúsculas, con/sin espacios, con/sin paréntesis)
     */
    private function getColorForSocial(string $nombre): ?string
    {
        $nombreLimpio = $this->limpiarNombreRed($nombre);

        $coloresPorRed = [
            // Facebook
            'facebook' => '#1877F2',
            'fb' => '#1877F2',
            'meta' => '#1877F2',

            // Instagram
            'instagram' => '#E4405F',
            'ig' => '#E4405F',

            // Twitter / X
            'twitter' => '#1DA1F2',
            'x' => '#000000',

            // LinkedIn
            'linkedin' => '#0A66C2',
            'ln' => '#0A66C2',

            // YouTube
            'youtube' => '#FF0000',
            'yt' => '#FF0000',

            // TikTok
            'tiktok' => '#000000',
            'tk' => '#000000',

            // WhatsApp
            'whatsapp' => '#25D366',
            'wa' => '#25D366',
            'whats' => '#25D366',
            'wp' => '#25D366',

            // Telegram
            'telegram' => '#26A5E4',
            'tg' => '#26A5E4',

            // Snapchat
            'snapchat' => '#FFFC00',
            'snap' => '#FFFC00',

            // Pinterest
            'pinterest' => '#BD081C',
            'pin' => '#BD081C',

            // Reddit
            'reddit' => '#FF4500',

            // Discord
            'discord' => '#5865F2',

            // Twitch
            'twitch' => '#9146FF',

            // Spotify
            'spotify' => '#1DB954',

            // GitHub
            'github' => '#333333',
            'git' => '#333333',

            // Threads
            'threads' => '#000000',

            // Bluesky
            'bluesky' => '#0285FF',
            'bsky' => '#0285FF',

            // Mastodon
            'mastodon' => '#6364FF',

            // Signal
            'signal' => '#3A76F0',

            // WeChat
            'wechat' => '#07C160',

            // Line
            'line' => '#00B900',

            // VK
            'vk' => '#0077FF',
            'vkontakte' => '#0077FF',

            // Tumblr
            'tumblr' => '#35465C',
        ];

        return $coloresPorRed[$nombreLimpio] ?? null;
    }

    /**
     * Limpia el nombre de la red para hacer matching más flexible
     */
    private function limpiarNombreRed(string $nombre): string
    {
        // Convertir a minúsculas
        $nombre = strtolower($nombre);

        // Eliminar texto entre paréntesis (Claro), (Oscuro), etc.
        $nombre = preg_replace('/\([^)]+\)/', '', $nombre);

        // Eliminar espacios extras
        $nombre = trim($nombre);

        // Eliminar caracteres especiales y dejar solo letras y números
        $nombre = preg_replace('/[^a-z0-9]/', '', $nombre);

        return $nombre;
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            '6' => 'Últimos 6 meses',
            '12' => 'Últimos 12 meses',
        ];
    }

    public function getHeading(): string
    {
        $meses = match ($this->filter) {
            '12' => '12 meses',
            default => '6 meses',
        };

        return static::$heading . " (últimos {$meses})";
    }

    protected function getHeight(): int
    {
        return 300;
    }
}
