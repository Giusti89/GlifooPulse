<?php

namespace App\Filament\Usuario\Resources\SocialResource\Pages;

use App\Filament\Usuario\Resources\SocialResource;
use App\Models\Spot;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListSocials extends ListRecords
{
    protected static string $resource = SocialResource::class;
     public function mount(): void
    {
        parent::mount();

        $user = Auth::user();

        $spot = Spot::whereHas('suscripcion', fn ($query) =>
            $query->where('user_id', $user->id)
        )->first();

        if ($spot && (is_null($spot->slug) || $spot->slug === '')) {
            Notification::make()
                ->title("¡Requiere configuracion previa!")
                ->body('Antes de poder agregar redes sociales, debes  ingresar a la configuración inicial.')
                ->icon('heroicon-o-user')
                ->persistent()
                ->iconColor('warning')
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(fn () =>
                    Spot::whereHas('suscripcion', fn ($query) =>
                        $query->where('user_id', Auth::id())
                    )
                    ->whereNotNull('slug')
                    ->where('slug', '!=', '')
                    ->exists()
                    
                ),
        ];
    }
}
