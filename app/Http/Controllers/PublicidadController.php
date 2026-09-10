<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Contenido;
use App\Models\Landing;
use App\Models\Portfolio;
use App\Models\Seo;
use App\Models\Social;
use App\Models\Spot;
use App\Models\SuportColor;
use App\Models\Video;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use App\Services\SocialClickService;
use Illuminate\Support\Facades\Storage;

class PublicidadController extends Controller
{
    public function show(Request $request, $slug)
    {
        try {
            // CERO CONSULTAS: Recuperamos el objeto con todas sus relaciones desde el Middleware
            $publicidad = $request->attributes->get('publicidad_precargada');

            // Si por alguna razón externa no viene del middleware, usamos un fallback seguro
            if (!$publicidad) {
                $publicidad = Spot::where('slug', $slug)
                    ->with(['colors', 'contenido', 'seo', 'suscripcion.user', 'suscripcion.paquete', 'socials.tipoRed', 'videos', 'portfolios', 'horarios'])
                    ->firstOrFail();
            }

            // Extraemos las relaciones instantáneamente desde la memoria RAM
            $color = $publicidad->colors;
            $contenido = $publicidad->contenido;
            $redes = $publicidad->socials;
            $portfolios = $publicidad->portfolios;
            $videos = $publicidad->videos;
            $catalogos = $publicidad->seo;

            // Buscamos la landing (No es relación Eloquent en tu modelo)
            $tipopublicidad = Landing::find($publicidad->tipolanding);

            $spot = $publicidad;

            // Filtrado de videos de portafolios en memoria
            $videoportfolio = $portfolios->pluck('url_embed')->filter()->values();

            $titulo = $publicidad->titulo;
            $usuarioSpot = optional(optional($publicidad->suscripcion)->user);
            $marca = optional($tipopublicidad)->nombre;

            // Grupo y plantilla
            $grupo = Str::slug($tipopublicidad->grupo ?? 'basico');
            $plantilla = Str::slug($tipopublicidad->nombre ?? 'default');
            $vista = "plantillas.$grupo.$plantilla";

            // Nivel de SEO según el paquete
            $seoNivel = optional($publicidad->suscripcion->paquete)->seo_level ?? 'basico';

            // Valores base (SEO básico)
            $tituloSEO = $catalogos->seo_title ?? $publicidad->titulo;
            $descripcionSEO = $catalogos->seo_descripcion ?? $contenido->descripcion ?? "";
            $keywordsSEO = $catalogos->seo_keyword ?? '';
            $robots = 'index, follow';
            $imagenOg = $contenido && $contenido->banner_url
                ? asset('storage/' . $contenido->logo_url)
                : asset('img/logos/Boton.ico');

            $locale = 'es_ES';
            $categoriapro = collect();
            $horarios = collect();

            // 3. Optimizamos la carga condicional para el catálogo
            if ($grupo === 'catalogo') {
                // Cargamos categorías -> productos -> imágenes filtrando por el spot_id
                $categoriapro = Categoria::with(['productos.imagenes'])
                    ->where('spot_id', $publicidad->id)
                    ->orderBy('orden', 'asc')
                    ->get();

                // REEMPLAZO CLAVE: Usamos la relación ya cargada como colección con ->get() viejo cambiado a ->horarios
                $horarios = $publicidad->horarios;
            }

            $estadoTienda = $publicidad->obtenerEstadoActual();

            // Ajustes según nivel SEO (Se conserva tu lógica intacta)
            if ($seoNivel === 'basico') {
                $tituloSEO = Str::limit($tituloSEO, 60, "");
                $descripcionSEO = Str::limit($descripcionSEO, 150, "");
                $robots = 'index, follow';
                $imagenOg = asset('img/logos/Boton.ico');
            }

            if ($seoNivel === 'medio') {
                $tituloSEO = Str::limit($tituloSEO, 65, "");
                $descripcionSEO = Str::limit($descripcionSEO, 160, "");
                $robots = $catalogos->seo_robots ?? 'index, follow';
                $imagenOg = asset('img/logos/Boton.ico');
            }

            if ($seoNivel === 'completo') {
                $tituloSEO = Str::limit($tituloSEO, 65, "");
                $descripcionSEO = Str::limit($descripcionSEO, 170, "");
                $robots = $catalogos->seo_robots ?? 'index, follow';
                $imagenOg = $contenido && $contenido->banner_url
                    ? asset('storage/' . $contenido->logo_url)
                    : null;
                $locale = $catalogos->seo_locale ?? 'es_ES';

                if (request()->has('prod')) {
                    $productSlug = request()->query('prod');
                    $productoSEO = $categoriapro->flatMap->productos->firstWhere('slug', $productSlug);

                    if ($productoSEO) {
                        $tituloSEO = $productoSEO->nombre . " | " . $publicidad->titulo;
                        $descripcionSEO = Str::limit($productoSEO->descripcion, 150, '...');

                        $imagenRelacionSEO = $productoSEO->imagenes->first();
                        if ($imagenRelacionSEO && !empty($imagenRelacionSEO->url)) {
                            $imagenOg = Storage::url($imagenRelacionSEO->url);
                        } else {
                            $imagenOg = $contenido && $contenido->banner_url
                                ? Storage::url($contenido->banner_url)
                                : asset('img/logos/Boton.ico');
                        }
                    }
                }
            }

            $ogUrl = request()->url();
            $ogType = ($grupo === 'catalogo') ? 'business.business' : 'profile';

            if (!View::exists($vista)) {
                return redirect()->route('inicio')->with('msj', 'noexiste');
            }

            if ($publicidad->estado || Auth::id() == optional($usuarioSpot)->id) {
                if (!Auth::check() || Auth::id() !== optional($usuarioSpot)->id) {
                    dispatch(function () use ($publicidad) {
                        $publicidad->incrementarVisita();
                    })->afterResponse();
                }
                $dataCompact = compact(
                    'titulo',
                    'catalogos',
                    'contenido',
                    'categoriapro',
                    'tituloSEO',
                    'horarios',
                    'estadoTienda',
                    'descripcionSEO',
                    'keywordsSEO',
                    'redes',
                    'robots',
                    'imagenOg',
                    'locale',
                    'videos',
                    'ogUrl',
                    'ogType',
                    'spot',
                    'color'
                );

                if ($grupo === "portfolio") {
                    $dataCompact['portfolios'] = $portfolios;
                    $dataCompact['videoportfolio'] = $videoportfolio;
                }

                return view($vista, $dataCompact);
            } else {
                return redirect()->route('inicio')->with('msj', 'noactivo');
            }
        } catch (\Exception $e) {
            if (app()->environment('local')) {
                throw $e; // Te ayudará a ver errores reales en desarrollo
            }
            return redirect()->route('inicio')->with('msj', 'pagvencida');
        }
    }


    public function redirecion(string $encryptedId, SocialClickService $clickService)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $social = Social::findOrFail($id);

            $clickService->registerClick($social);

            return redirect()->away($social->url);
        } catch (\Throwable $th) {
            return response()->view('errors.500', [], 500);
        }
    }
}
