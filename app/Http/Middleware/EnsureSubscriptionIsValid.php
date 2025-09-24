<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class EnsureSubscriptionIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. Si no hay usuario autenticado, deja que pase al flujo normal (Filament mandará al login).
        if (!$user) {
            return Redirect::route('inicio')->with('msj', 'prohibido');
        }

        // 2. Si no tiene suscripción
        if (!$user->suscripcion) {
            return $this->forceLogout($request, 'sinsuscripcion');
        }

        // 3. Si la suscripción no está activa
        if (!$user->tieneSuscripcionActiva()) {
            return $this->forceLogout($request, 'sinsuscripcion');
        }

        // 4. Todo correcto → añade headers de no-cache
        return $this->noCache($next($request));
    }

    /**
     * Forzar cierre de sesión y redirigir con mensaje.
     */
    protected function forceLogout(Request $request, string $mensaje): Response
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('inicio')->with('msj', $mensaje);
    }

    /**
     * Evitar cache en páginas protegidas.
     */
    protected function noCache(Response $response): Response
    {
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }
}
