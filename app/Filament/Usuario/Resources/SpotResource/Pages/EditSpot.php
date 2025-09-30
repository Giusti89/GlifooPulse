<?php

namespace App\Filament\Usuario\Resources\SpotResource\Pages;

use App\Filament\Usuario\Resources\SpotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpot extends EditRecord
{
    protected static string $resource = SpotResource::class;


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
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
}
