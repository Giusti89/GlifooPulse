<?php

namespace App\Filament\Portfolio\Resources\SpotResource\Pages;

use App\Filament\Portfolio\Resources\SpotResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSpot extends CreateRecord
{
  protected static string $resource = SpotResource::class;
  protected function getRedirectUrl(): string
  {
    return $this->getResource()::getUrl('index');
  }
  protected function afterSave(): void
  {
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
    // 2) Guardar logo_url en contenido
    if (! empty($state['logo_url'])) {
      $this->record->contenido()->updateOrCreate(
        ['spot_id' => $this->record->getKey()],
        [
          'logo_url'  => $state['logo_url']  ?? null,
          'background' => $state['background'] ?? '#ffffff',
          'ctexto'    => $state['ctexto']    ?? '#ffffff',
          'colsecond'    => $state['colsecond']    ?? '#ffffff',
          'phone'  => $state['phone']  ?? null,
          'texto'  => $state['texto']  ?? null,
          'pie'  => $state['pie']  ?? null,
          'banner_url'  => $state['banner_url']  ?? null,
          'latitude'  => $state['latitude']  ?? null,
          'longitude'  => $state['longitude']  ?? null,
        ]
      );
    }
  }
}
