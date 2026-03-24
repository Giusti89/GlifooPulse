<?php

namespace App\Filament\Catalogo\Resources\VideosResource\Pages;

use App\Filament\Catalogo\Resources\VideosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVideos extends EditRecord
{
    protected static string $resource = VideosResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $url = $data['url'];

        // --- YOUTUBE ---
        if (
            preg_match('/youtu\.be\/([^?]+)/', $url, $m) ||
            preg_match('/youtube\.com\/.*v=([^&]+)/', $url, $m)
        ) {
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

        if ($this->isTikTokUrl($url)) {
            $result = $this->processTikTokUrl($url);
            $data['proveedor'] = $result['proveedor'];
            $data['url_embed'] = $result['url_embed'];
            return $data;
        }

        $data['proveedor'] = 'otro';
        $data['url_embed'] = $url;

        return $data;
    }
    protected function isTikTokUrl(string $url): bool
    {
        return str_contains($url, 'tiktok.com') ||
            str_contains($url, 'vt.tiktok.com');
    }

    protected function processTikTokUrl(string $url): array
    {
        $cleanUrl = explode('?', $url)[0];

        // Formato 1: tiktok.com/@usuario/video/123456789
        if (preg_match('/\/video\/(\d+)/', $cleanUrl, $matches)) {
            $videoId = $matches[1];
            return [
                'proveedor' => 'tiktok',
                'url_embed' => "https://www.tiktok.com/embed/v2/{$videoId}"
            ];
        }

        // Formato 2: tiktok.com/v/123456789.html
        if (preg_match('/\/v\/(\d+)/', $cleanUrl, $matches)) {
            $videoId = $matches[1];
            return [
                'proveedor' => 'tiktok',
                'url_embed' => "https://www.tiktok.com/embed/v2/{$videoId}"
            ];
        }

        // Formato 3: tiktok.com/t/ZT1234567/ (URL corta)
        if (preg_match('/\/t\/([A-Za-z0-9]+)/', $cleanUrl, $matches)) {
            $videoId = $matches[1];
            return [
                'proveedor' => 'tiktok',
                'url_embed' => "https://www.tiktok.com/embed/v2/{$videoId}"
            ];
        }

        // Formato 4: vt.tiktok.com/ZS1234567/ (acortada)
        if (preg_match('/vt\.tiktok\.com\/([A-Za-z0-9]+)/', $cleanUrl, $matches)) {
            // Para URLs acortadas, devolvemos la original (frontend resolverá con oEmbed)
            return [
                'proveedor' => 'tiktok',
                'url_embed' => $url
            ];
        }

        // Si no se pudo procesar, devolver original
        return [
            'proveedor' => 'tiktok',
            'url_embed' => $url
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
