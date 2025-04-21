<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Checkfecha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $fechaActual = Carbon::now();

        $fechaInscripcion = Carbon::parse($user->suscripcion->fecha_inicio);
        $fechaFinalizacion = Carbon::parse($user->suscripcion->fecha_fin);

        $diferenciaDias = Carbon::parse($fechaActual)->diffInDays($fechaFinalizacion);


        if ($fechaActual->gte($fechaInscripcion) && $fechaActual->lte($fechaFinalizacion)) {

            if ($diferenciaDias <= 5) {
                
                Notification::make()
                    ->title('¡Le quedan, ' . $diferenciaDias . ' dias de suscripcion!')
                    ->icon('heroicon-o-user')
                    ->iconColor('danger')
                    ->send();
                return $next($request);
            }
            
            Notification::make()
                ->title('¡Bienvenido de nuevo, ' . $user->name . '!')
                ->icon('heroicon-o-user')
                ->iconColor('success')
                ->send();
            return $next($request);
        } else {
            $request->session()->forget('inicio');
            $request->session()->forget('final');

            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/')->withErrors([
                'subscription' => 'Tu suscripción ha vencido. Por favor, comunicate con el administrador del servicio.',
            ]);
        }
    }
}
