<?php

namespace App\Http\Controllers;

use App\Models\Landing;
use App\Models\Spot;
use Illuminate\Http\Request;

class PublicidadController extends Controller
{
    public function show($slug)
    {
        $publicidad = Spot::where('slug', $slug)->first();
        $tipopublicidad = Landing::where('id', $publicidad->tipolanding)->first();
        
        if (!$publicidad) {
            return redirect()->route('error');
        }
        
        $titulo = $publicidad->titulo;
        $id = $publicidad->user_id;
        $marca = $tipopublicidad->nombre;
        // user.suscripciones.paquete.landing.nombre
        // dd($nombreCliente, $titulo, $id, $marca);
        if ($marca == "Glifoo basic") {
            return view("/basico", compact('titulo'));
        } elseif ($marca == "Glifoo bussines") {
            return view("/basico", compact('titulo'));
        }
    }
}
