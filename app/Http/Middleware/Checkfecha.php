<?php

namespace App\Http\Middleware;

use App\Models\Renewal;
use App\Models\Suscripcion;
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

        if (!$user || !$user->suscripcion) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::route('inicio');
        }

        $fechaActual = Carbon::now();
        $inicio = Carbon::parse($user->suscripcion->fecha_inicio);
        $fin = Carbon::parse($user->suscripcion->fecha_fin);
        $diasRestantes = $fechaActual->diffInDays($fin, false);

        $tieneRenovacionPendiente = Renewal::whereHas('suscripcion', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->where('estado', 'pendiente')
            ->exists();

        if ($fechaActual->between($inicio, $fin)) {

            // Mostrar la advertencia si quedan pocos días y aún no fue notificado
            if ($diasRestantes <= 5 && !$tieneRenovacionPendiente) {
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

            return $next($request);
        }

        $userId = Auth::id();
        $encryptedId = Crypt::encrypt($userId);

        $suscripcion = Suscripcion::where('user_id', $userId)->first();
        $suscripcion->update(['estado' => '0']);

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return Redirect::route('resuscrip', ['renovacion' => $encryptedId])->with('msj', 'susterminada');
    }
}
