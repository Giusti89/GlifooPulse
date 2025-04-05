<?php

namespace App\Http\Controllers;

use App\Models\Contenido;
use App\Models\Landing;
use App\Models\Spot;
use Illuminate\Http\Request;

class PublicidadController extends Controller
{
    public function show($slug)
    {
        $publicidad = Spot::where('slug', $slug)->first();
        $tipopublicidad = Landing::where('id', $publicidad->tipolanding)->first();
        $contenido=Contenido::where('spot_id',$publicidad->id)->first();
        
        
        if (!$publicidad) {
            return redirect()->route('error');
        }
        
        $titulo = $publicidad->titulo;
        $usuario = $publicidad->suscripcion->user->name;
        $id = $publicidad->user_id;
        $marca = $tipopublicidad->nombre;
        
      
        if ($marca == "Glifoo basic") {
            return view("/basico", compact('titulo','contenido'));
        } elseif ($marca == "Glifoo bussines") {
            return view("/basico", compact('titulo'));
        }
    }
}
