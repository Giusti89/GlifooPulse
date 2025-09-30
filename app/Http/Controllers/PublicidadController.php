<?php

namespace App\Http\Controllers;

use App\Models\Contenido;
use App\Models\Landing;
use App\Models\Seo;
use App\Models\Social;
use App\Models\Spot;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class PublicidadController extends Controller
{
    public function show($slug)
    {
        try {
            // 🔹 Obtenemos la publicidad principal
            $publicidad = Spot::where('slug', $slug)->firstOrFail();
            $tipopublicidad = Landing::find($publicidad->tipolanding);
            $contenido = Contenido::where('spot_id', $publicidad->id)->first();
            $redes = Social::where('spot_id', $publicidad->id)->with('tipoRed')->get();

            $titulo = $publicidad->titulo;
            $usuarioSpot = optional(optional($publicidad->suscripcion)->user);
            $marca = optional($tipopublicidad)->nombre;

            // 🔹 Grupo y plantilla (para cargar la vista correcta)
            $grupo = Str::slug($tipopublicidad->grupo ?? 'basico');
            $plantilla = Str::slug($tipopublicidad->nombre ?? 'default');
            $vista = "plantillas.$grupo.$plantilla";

            // 🔹 SEO dinámico
            $catalogos = Seo::where('spot_id', $publicidad->id)->first();

            // Nivel de SEO según el paquete
            $seoNivel = optional($publicidad->suscripcion->paquete)->tipo_estadisticas ?? 'basico';

            // Valores base (SEO básico)
            $tituloSEO = $catalogos->seo_title ?? $publicidad->titulo;
            $descripcionSEO = $catalogos->seo_descripcion ?? $contenido->descripcion ?? '';
            $keywordsSEO = $catalogos->seo_keyword ?? '';
            $robots = 'index, follow';
            $imagenOg = null;
            $locale = 'es_ES';

            // 🔹 Ajustes según nivel SEO
            if ($seoNivel === 'basico') {
                // Solo título y descripción básica
                $tituloSEO = Str::limit($tituloSEO, 60, '');
                $descripcionSEO = Str::limit($descripcionSEO, 160, '');
            }

            if ($seoNivel === 'medio') {
                $tituloSEO = Str::limit($tituloSEO, 60, '');
                $descripcionSEO = Str::limit($descripcionSEO, 160, '');
                $robots = $catalogos->seo_robots ?? 'index, follow';
            }

            if ($seoNivel === 'avanzada') {
                $tituloSEO = Str::limit($tituloSEO, 60, '');
                $descripcionSEO = Str::limit($descripcionSEO, 160, '');
                $robots = $catalogos->seo_robots ?? 'index, follow';
                $imagenOg = '/storage/' . ($contenido->banner_url ?? '');
                $locale = $catalogos->seo_locale ?? 'es_ES';
            }

            // 🔹 Verificamos si existe la vista
            if (!View::exists($vista)) {
                if (!Auth::check() || Auth::id() !== optional($usuarioSpot)->id) {
                    $publicidad->incrementarVisita();
                }
                return redirect()->route('inicio')->with('msj', 'noexiste');
            }

            // 🔹 Validamos si la publicidad está activa
            if ($publicidad->estado || Auth::id() == optional($usuarioSpot)->id) {
                if ($grupo === "catalogo") {
                    if (!Auth::check() || Auth::id() !== optional($usuarioSpot)->id) {
                        $publicidad->incrementarVisita();
                    }
                    return view($vista, compact(
                        'titulo',
                        'catalogos',
                        'tituloSEO',
                        'descripcionSEO',
                        'keywordsSEO',
                        'robots',
                        'imagenOg',
                        'locale'
                    ));
                } else {
                    if (!Auth::check() || Auth::id() !== optional($usuarioSpot)->id) {
                        $publicidad->incrementarVisita();
                    }
                    return view($vista, compact(
                        'titulo',
                        'contenido',
                        'redes',
                        'catalogos',
                        'tituloSEO',
                        'descripcionSEO',
                        'keywordsSEO',
                        'robots',
                        'imagenOg',
                        'locale'
                    ));
                }
            } else {
                return redirect()->route('inicio')->with('msj', 'noactivo');
            }
        } catch (\Exception $e) {
            Log::error("Error en PublicidadController@show: " . $e->getMessage());
            return redirect()->route('inicio')->with('msj', 'pagvencida');
        }
    }

    public function redirecion(string $encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $social = Social::findOrFail($id);
            $social->increment('clicks');
            return redirect()->away($social->url);
        } catch (\Throwable $th) {
            return response()->view('errors.500', [], 500);
        }
    }
}
