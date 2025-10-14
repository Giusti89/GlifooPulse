<?php

namespace App\Http\Controllers;

use App\Models\Landing;
use App\Models\Paquete;
use App\Models\Producto;
use App\Models\Spot;
use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function index()
    {
        $totalLanding    = Landing::count();
        $totalCatalogos  = Producto::count();
        $totalClientes   = Spot::where('estado', true)->count();
        $clientesActivos = Spot::where('estado', true)->take(8)->get();
        $paquetes=Paquete::where('estado',true)->get();      
        

        return view('inicio', compact(
            'totalLanding',
            'totalCatalogos',
            'totalClientes',
            'clientesActivos',
            'paquetes',            
        ));
    }
}
