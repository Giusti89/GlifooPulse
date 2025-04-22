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

        if (!$user->suscripcion) {
            Auth::logout();
            return redirect('/')->withErrors([
                'subscription' => 'No tienes una suscripción activa. Por favor, contacta al administrador.',
            ]);
        }

        $fechaActual = Carbon::now();
        $inicio = Carbon::parse($user->suscripcion->fecha_inicio);
        $fin = Carbon::parse($user->suscripcion->fecha_fin);
        $diasRestantes = $fechaActual->diffInDays($fin, false);

        if ($fechaActual->between($inicio, $fin)) {

            // Mostrar la advertencia si quedan pocos días y aún no fue notificado
            if ($diasRestantes <= 5 && !session()->has('notificado_suscripcion')) {
                session()->put('notificado_suscripcion', true);

                Notification::make()
                    ->title("¡Le quedan $diasRestantes días de suscripción!")
                    ->icon('heroicon-o-user')
                    ->iconColor('danger')
                    ->send();
            }

            if (!session()->has('notificado_bienvenida')) {
                session()->put('notificado_bienvenida', true);

                Notification::make()
                    ->title("¡Bienvenido de nuevo, {$user->name}!")
                    ->icon('heroicon-o-user')
                    ->iconColor('success')
                    ->send();
            }

            return $next($request);
        }
        session()->forget(['inicio', 'final']);
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/')->withErrors([
            'subscription' => 'Tu suscripción ha vencido. Por favor, comunicate con el administrador del servicio.',
        ]);
    }
}
