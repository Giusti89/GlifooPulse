<?php

namespace App\Filament\Catalogo\Resources\VideosResource\Pages;

use App\Filament\Catalogo\Resources\VideosResource;
use App\Models\Spot;
use App\Models\Video;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class CreateVideos extends CreateRecord
{
    protected static string $resource = VideosResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();


        $suscripcion = $user->getSuscripcionActiva();
        if (! $suscripcion) {
            Notification::make()
                ->title('No tienes suscripción activa')
                ->danger()
                ->send();
            $this->halt();
        }

        $spot = Spot::where('suscripcion_id', $suscripcion->id)->first();
        if (! $spot) {
            Notification::make()
                ->title('No se encontró proyecto asociado')
                ->danger()
                ->send();
            $this->halt();
        }

        $data['spot_id'] = $spot->id;

        $url = $data['url'];

        // --- YOUTUBE (normal) ---
        if (preg_match('/youtube\.com\/watch\?v=([^\&]+)/', $url, $m)) {
            $videoId = $m[1];
            $data['proveedor'] = 'youtube';
            $data['url_embed'] = "https://www.youtube.com/embed/" . $videoId;
            return $data;
        }

        // --- YOUTUBE (corto: youtu.be) ---
        if (preg_match('/youtu\.be\/([^?]+)/', $url, $m)) {
            $videoId = $m[1];
            $data['proveedor'] = 'youtube';
            $data['url_embed'] = "https://www.youtube.com/embed/" . $videoId;
            return $data;
        }

        // --- VIMEO ---
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
            $videoId = $m[1];
            $data['proveedor'] = 'vimeo';
            $data['url_embed'] = "https://player.vimeo.com/video/" . $videoId;
            return $data;
        }
        // --- TIKTOK  ---
        if ($this->isTikTokUrl($url)) {
            $data['proveedor'] = 'tiktok';
            $data['url_embed'] = $this->processTikTokUrl($url);
            return $data;
        }

        // --- Si no coincide con YouTube o Vimeo ---
        $data['proveedor'] = 'otro';
        $data['url_embed'] = $url;

        return $data;
    }

    protected function isTikTokUrl(string $url): bool
    {
        return str_contains($url, 'tiktok.com') ||
            str_contains($url, 'vt.tiktok.com');
    }

    protected function processTikTokUrl(string $url): string
    {
        $cleanUrl = explode('?', $url)[0];

        $patterns = [
            '/\/video\/(\d+)/',    // https://www.tiktok.com/@user/video/123456789
            '/\/v\/(\d+)/',       // https://www.tiktok.com/v/123456789.html  
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $cleanUrl, $matches)) {
                $videoId = $matches[1];
                // ✅ EMBED OFICIAL TikTok v2 (IMPORTANTE: sin /embed/)
                return "https://www.tiktok.com/@tiktok/video/{$videoId}";
                // O también puedes usar:
                // return "https://www.tiktok.com/video/{$videoId}";
            }
        }

        return $url;
    }

    protected function beforeCreate(): void
    {
        $user = Auth::user();

        $suscripcion = $user->getSuscripcionActiva();


        $videos = Video::whereHas('spot', function ($q) use ($user) {
            $q->whereHas('suscripcion', function ($q2) use ($user) {
                $q2->where('user_id', $user->id);
            });
        })
            ->with('spot')
            ->get();

        $spots = $videos->pluck('spot')->flatten();

        if ($suscripcion?->estado == 1) {

            $max = $suscripcion->paquete?->max_videos;

            if ($max !== null) {
                $count = $spots->count();

                if ($count >= $max) {
                    Notification::make()
                        ->title('Has alcanzado el máximo de videos permitidos en tu plan.')
                        ->danger()
                        ->persistent()
                        ->actions([
                            Action::make('renovar')
                                ->label('Regresar')
                                ->button()
                                ->color('primary')
                                ->url(route('filament.catalogo.resources.videos.index'))
                        ])
                        ->send();

                    $this->halt();
                }
            }
        }
    }
}
