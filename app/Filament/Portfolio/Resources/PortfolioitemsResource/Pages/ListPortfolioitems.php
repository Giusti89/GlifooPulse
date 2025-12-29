<?php

namespace App\Filament\Portfolio\Resources\PortfolioitemsResource\Pages;

use App\Filament\Portfolio\Resources\PortfolioitemsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPortfolioitems extends ListRecords
{
    protected static string $resource = PortfolioitemsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
