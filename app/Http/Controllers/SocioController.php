<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SocioController extends Controller
{
    public function index()
{
    $results = DB::table('spots')
        ->join('suscripcions', 'spots.suscripcion_id', '=', 'suscripcions.id')
        ->join('users', 'suscripcions.user_id', '=', 'users.id')
        ->select(
            'spots.id',
            'spots.titulo',
            'spots.slug as spot_slug',
            'users.name as user_name',
            'users.lastname as user_lastname',
            'suscripcions.fecha_inicio',
            'suscripcions.fecha_fin',
            'spots.estado as spot_status'
        )
        ->get();
    
    return view('socios/index', compact('results'));
}
}
