<?php

namespace App\Filament\Portfolio\Resources\SuportColorResource\Pages;

use App\Filament\Portfolio\Resources\SuportColorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuportColor extends EditRecord
{
    protected static string $resource = SuportColorResource::class;

    protected function getRedirectUrl(): ?string
    {
         return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Buscamos el modelo Contenido a través de la relación con Spot
        $contenido = $this->record->spot?->contenido;

        if ($contenido) {
            $data['background'] = $contenido->background;
            $data['colsecond'] = $contenido->colsecond;
            $data['ctexto'] = $contenido->ctexto;
        }

        return $data;
    }
    protected function afterSave(): void
    {
        $contenido = $this->record->spot?->contenido;

        if ($contenido) {
            $contenido->update([
                'background' => $this->form->getState()['background'],
                'colsecond' => $this->form->getState()['colsecond'],
                'ctexto' => $this->form->getState()['ctexto'],
            ]);
        }

        $slug = $this->record->spot?->slug;

        if ($slug) {
            $url = route('publicidad', ['slug' => $slug]);

            $this->js("window.open('{$url}', '_blank');");
        }
    }
}
