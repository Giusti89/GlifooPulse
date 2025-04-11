<?php

namespace App\Http\Controllers;

use App\Models\Contenido;
use App\Models\Landing;
use App\Models\Social;
use App\Models\Spot;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PublicidadController extends Controller
{
    public function show($slug)
    {
        $publicidad = Spot::where('slug', $slug)->first();
        $tipopublicidad = Landing::where('id', $publicidad->tipolanding)->first();
        $contenido = Contenido::where('spot_id', $publicidad->id)->first();

        $redes = Social::where('spot_id', $publicidad->id)->get();


        if (!$publicidad) {
            return redirect()->route('error');
        }

        $titulo = $publicidad->titulo;
        $usuario = $publicidad->suscripcion->user->name;
        $id = $publicidad->user_id;
        $marca = $tipopublicidad->nombre;


        if ($marca == "Glifoo basic") {
            Visit::create([
                'spot_id' => $publicidad->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'visited_at' => now(),
            ]);
            return view("/basico", compact('titulo', 'contenido', 'redes'));
        } elseif ($marca == "Glifoo bussines") {
            return view("/basico", compact('titulo'));
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
