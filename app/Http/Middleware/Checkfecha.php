<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Redirect;


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
            return Redirect::route('inicio')->with('msj', 'sinsuscripcion');
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
                    ->persistent()
                    ->actions([
                        Action::make('renovar')
                            ->label('Renovar ahora')  // Texto del botón
                            ->button()  // Estilo de botón
                            ->color('primary')  // Color (opcional)
                            ->url(route('renovacion.form'))  // URL del formulario
                    ])
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

        $userId = Auth::id();
        $encryptedId = Crypt::encrypt($userId);

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return Redirect::route('resuscrip', ['renovacion' => $encryptedId]);
    }
}
