<?php

namespace App\Filament\Catalogo\Resources\SpotResource\Pages;

use App\Filament\Catalogo\Resources\SpotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpot extends EditRecord
{
    protected static string $resource = SpotResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data = parent::mutateFormDataBeforeFill($data);

        // Si existe un registro SEO, agrégalo al state
        if ($seo = $this->record->seo) {
            $data['descripcion']      = $seo->descripcion;
            $data['seo_title']        = $seo->seo_title;
            $data['seo_descripcion']  = $seo->seo_descripcion;
            $data['seo_keyword']      = $seo->seo_keyword;
        }

        if ($contenido = $this->record->contenido) {
            $data['logo_url']      = $contenido->logo_url;
            $data['background']        = $contenido->background;
            $data['ctexto']  = $contenido->ctexto;
            $data['colsecond']  = $contenido->colsecond;
            $data['phone']  = $contenido->phone;
            $data['banner_url']      = $contenido->banner_url;

        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = auth()->user();


        if ($user->suscripcion?->paquete->seo_level !== 'completo') {
            unset($data['seo_keyword']);
        }

        if ($user->suscripcion?->paquete->seo_level === 'basico') {
            unset($data['seo_descripcion']);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        // 1) obtén todo el state
        $state = $this->form->getState();

        // 2) actualiza o crea el registro SEO
        $this->record->seo()->updateOrCreate(
            ['spot_id' => $this->record->getKey()],
            [
                'descripcion'     => $state['descripcion']     ?? null,
                'seo_title'       => $state['seo_title']       ?? null,
                'seo_descripcion' => $state['seo_descripcion'] ?? null,
                'seo_keyword'     => $state['seo_keyword']     ?? null,
            ]
        );


        $this->record->contenido()->updateOrCreate(
            ['spot_id' => $this->record->getKey()],
            [
                'logo_url'     => $state['logo_url']     ?? null,
                'background'       => $state['background']       ?? null,
                'ctexto' => $state['ctexto'] ?? null,
                'colsecond' => $state['colsecond'] ?? null,
                'phone' => $state['phone'] ?? null,
                'banner_url'     => $state['banner_url']     ?? null,             
            ]
        );
    }
}
