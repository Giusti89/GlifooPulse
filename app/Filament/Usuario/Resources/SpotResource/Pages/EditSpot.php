<?php

namespace App\Filament\Usuario\Resources\SpotResource\Pages;

use App\Filament\Usuario\Resources\SpotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpot extends EditRecord
{
    protected static string $resource = SpotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
