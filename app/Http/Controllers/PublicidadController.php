<?php

namespace App\Http\Controllers;

use App\Models\Contenido;
use App\Models\Landing;
use App\Models\Social;
use App\Models\Spot;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class PublicidadController extends Controller
{
    public function show($slug)
    {
        try {
            $publicidad = Spot::where('slug', $slug)->first();

            if (!$publicidad) {
                return redirect()->route('error');
            }

            $tipopublicidad = Landing::find($publicidad->tipolanding);
            $contenido = Contenido::where('spot_id', $publicidad->id)->first();
            $redes = Social::where('spot_id', $publicidad->id)->get();

            $titulo = $publicidad->titulo;
            $usuario = optional(optional($publicidad->suscripcion)->user)->name;
            $id = $publicidad->user_id;
            $marca = optional($tipopublicidad)->nombre;

            if ($marca == "Glifoo basic") {
                $usuarioSpot = optional(optional($publicidad->suscripcion)->user);

                if (!Auth::check() || Auth::id() !== optional($usuarioSpot)->id) {
                    Visit::create([
                        'spot_id' => $publicidad->id,
                        'ip' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                        'visited_at' => now(),
                    ]);
                }
                return view("/basico", compact('titulo', 'contenido', 'redes'));
            } elseif ($marca == "Glifoo bussines") {
                return view("/basico", compact('titulo'));
            } else {
                return redirect()->route('error');
            }
        } catch (\Exception $e) {
            Log::error("Error en PublicidadController@show: " . $e->getMessage());
            return redirect()->route('error')->with('error', 'Ocurrió un error al mostrar la publicidad.');
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
