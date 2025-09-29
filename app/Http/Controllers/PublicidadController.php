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
            $publicidad = Spot::where('slug', $slug)->first();
            $tipopublicidad = Landing::find($publicidad->tipolanding);
            $contenido = Contenido::where('spot_id', $publicidad->id)->first();
            $redes = Social::where('spot_id', $publicidad->id)->with('tipoRed')->get();

            $titulo = $publicidad->titulo;
            $usuarioSpot = optional(optional($publicidad->suscripcion)->user);
            $marca = optional($tipopublicidad)->nombre;

            $grupo = Str::slug($tipopublicidad->grupo ?? 'basico');
            $plantilla = Str::slug($tipopublicidad->nombre ?? 'default');
            $vista = "plantillas.$grupo.$plantilla";

            if ($grupo === "catalogo") {
                $catalogos = Seo::where('spot_id', $publicidad->id)->first();
            }

            if (!View::exists($vista)) {

                if (!Auth::check() || Auth::id() !== optional($usuarioSpot)->id) {
                    $publicidad->incrementarVisita();
                }

                return redirect()->route('inicio')->with('msj', 'pagvencida');
            }
            if ($publicidad->estado || Auth::id() == optional($usuarioSpot)->id) {

                if ($grupo === "catalogo") {
                    return view($vista, compact('titulo', 'catalogos'));
                } else {
                    return view($vista, compact('titulo', 'contenido', 'redes'));
                }
            } else {

                return redirect()->route('inicio')->with('msj', 'pagvencida');
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
