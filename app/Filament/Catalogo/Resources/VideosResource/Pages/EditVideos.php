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

        return $data;
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
