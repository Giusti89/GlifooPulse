<?php

namespace App\Filament\Portfolio\Resources\SuportColorResource\Pages;

use App\Filament\Portfolio\Resources\SuportColorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSuportColor extends CreateRecord
{
    protected static string $resource = SuportColorResource::class;

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
    }
}
