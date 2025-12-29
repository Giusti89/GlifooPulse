<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Filament\Pages\Dashboard;

class RedirectToProperPanelMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            $currentPanel = filament()->getCurrentPanel()?->getId();

            // 1. Administrador → debe estar en panel admin
            if ($user->nombreRol() === "Administrador General" && $currentPanel !== 'admin') {
                return redirect()->to(Dashboard::getUrl(panel: 'admin'));
            }

            // 2. Usuario con Catálogos → debe estar en panel catalogos
            if ($user->tieneTipoproducto('Catalogo') && $currentPanel !== 'catalogo') {
                return redirect()->to(Dashboard::getUrl(panel: 'catalogo'));
            }

            // 3. Usuario con Biolink → debe estar en panel usuario
            if ($user->tieneTipoproducto('Landing page') && $currentPanel !== 'usuario') {
                return redirect()->to(Dashboard::getUrl(panel: 'usuario'));
            }

             if ($user->tieneTipoproducto('Portfolio') && $currentPanel !== 'portfolio') {
                return redirect()->to(Dashboard::getUrl(panel: 'portfolio'));
            }
        }

        // Si ya está en el panel correcto, simplemente continúa
        return $next($request);
    }
}
