<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function index()
    {
        $totalLanding    = \App\Models\Landing::count();
        $totalCatalogos  = \App\Models\Producto::count();
        $totalClientes   = \App\Models\Spot::where('estado', true)->count();
        $clientesActivos = \App\Models\Spot::where('estado', true)->take(8)->get();
        

        return view('inicio', compact(
            'totalLanding',
            'totalCatalogos',
            'totalClientes',
            'clientesActivos',
            
        ));
    }
}
