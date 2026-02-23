<?php

namespace App\Http\Controllers;

use App\Models\Landing;
use App\Models\Paquete;
use App\Models\Producto;
use App\Models\Spot;
use App\Models\User;
use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function index()
    {
        $totalLanding    = Landing::count();
        $totalCatalogos  = Producto::count();
        $paquetes = Paquete::where('estado', true)->get();
        


        return view('inicio', compact(
            'totalLanding',
            'totalCatalogos',            
            'paquetes',
        ));
    }
}
