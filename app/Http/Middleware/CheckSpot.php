<?php

namespace App\Http\Middleware;

use App\Models\Spot;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSpot
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $slug = $request->route('slug');

        $spot = \App\Models\Spot::where('slug', $slug)->first();

        if (!$spot || !$spot->suscripcion) {
            return redirect()->route('inicio')->with('msj', 'pagvencida');
        }

        $hoy = Carbon::now()->startOfDay();
        $fin = Carbon::parse($spot->suscripcion->fecha_fin)->startOfDay();

        // Si ya pasó la fecha fin, se considera vencida
        if ($hoy->gt($fin)) {
            return redirect()->route('inicio')->with('msj', 'pagvencida');
        }

        return $next($request);
    }
}
