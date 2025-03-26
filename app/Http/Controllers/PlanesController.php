<?php

namespace App\Http\Controllers;

use App\Models\Paquete;
use Illuminate\Http\Request;

class PlanesController extends Controller
{
    public function index()
    {
        $productos=Paquete::all();
        return view('productos/planes',compact('productos'));
    }

}
