<?php

namespace App\Filament\Portfolio\Resources\PortfolioitemsResource\Pages;

use App\Filament\Portfolio\Resources\PortfolioitemsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Crypt;

class CreatePortfolioitems extends CreateRecord
{
    protected static string $resource = PortfolioitemsResource::class;

    public function getTitle(): string 
    {
        return 'Crear foto'; 
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
