<?php

namespace App\Filament\Portfolio\Resources\PortfolioitemsResource\Pages;

use App\Filament\Portfolio\Resources\PortfolioitemsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPortfolioitems extends EditRecord
{
    protected static string $resource = PortfolioitemsResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
