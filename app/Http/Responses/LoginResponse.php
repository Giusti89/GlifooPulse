<?php

namespace App\Http\Responses;

use Filament\Pages\Dashboard;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use Filament\Http\Responses\Auth\LoginResponse as BaseLoginResponse;

class LoginResponse extends BaseLoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $user = auth()->user();

        // 1. Si es Administrador general → panel admin
        if ($user->nombreRol() == "Administrador General") {
            return redirect()->to(Dashboard::getUrl(panel: 'admin'));
        }

        // 2. Si tiene suscripción a Catálogos → panel catalogos
        if ($user->tieneTipoproducto('Catalogo')) {            
            return redirect()->to(Dashboard::getUrl(panel: 'catalogo'));
        }

        // 3. Si tiene suscripción a Biolink → panel usuario (actual)
        if ($user->tieneTipoproducto('Landing page')) {
            return redirect()->to(Dashboard::getUrl(panel: 'usuario'));
        }

        return parent::toResponse($request);
    }
}
