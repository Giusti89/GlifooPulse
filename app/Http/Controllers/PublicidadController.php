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
        $usuario = $publicidad->user->id;
        if (!$publicidad) {
            return redirect()->route('error');
        }
        $nombreCliente = $publicidad->user->name;
        $titulo = $publicidad->titulo;
        $id = $publicidad->user_id;
        $marca = $tipopublicidad->nombre;
        // user.suscripciones.paquete.landing.nombre
        // dd($nombreCliente, $titulo, $id, $marca);
        if ($marca == "Glifoo plan basic") {
            return view("/basico", compact('titulo','nombreCliente'));
        } elseif ($marca == "Glifoo plan medium") {
            return view("/basico", compact('titulo','nombreCliente'));
        }
    }
}
