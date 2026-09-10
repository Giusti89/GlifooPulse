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

        // Realizamos la consulta maestra con todas las relaciones AQUÍ
        $spot = Spot::where('slug', $slug)
            ->with([
                'colors',
                'contenido',
                'seo',
                'suscripcion.user',
                'suscripcion.paquete',
                'socials' => function ($query) {
                    $query->with('tipoRed');
                },
                'videos' => function ($query) {
                    $query->where('estado', 1)->orderBy('orden', 'asc');
                },
                'portfolios' => function ($query) {
                    $query->with(['galeria', 'dato'])->where('estado', 1)->orderBy('orden', 'asc');
                },
                'horarios'
            ])
            ->first();

        // Validamos existencia del spot y de su suscripción (ya cargada en memoria)
        if (!$spot || !$spot->suscripcion) {
            return redirect()->route('inicio')->with('msj', 'pagvencida');
        }

        $hoy = Carbon::now()->startOfDay();
        $fin = Carbon::parse($spot->suscripcion->fecha_fin)->startOfDay();

        if ($hoy->gt($fin)) {
            return redirect()->route('inicio')->with('msj', 'pagvencida');
        }

        // TRUCO CLAVE: Guardamos el objeto completamente cargado dentro del request
        $request->attributes->set('publicidad_precargada', $spot);

        return $next($request);
    }
}
