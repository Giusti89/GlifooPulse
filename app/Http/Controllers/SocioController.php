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
            ->join('contenidos', 'contenidos.spot_id', '=', 'spots.id')
            ->join('users', 'suscripcions.user_id', '=', 'users.id')
            ->select(
                'spots.id',
                'spots.estado',
                'spots.titulo',
                'spots.slug as spot_slug',
                'users.name as user_name',
                'contenidos.banner_url',
                'contenidos.logo_url',
                'contenidos.texto',
                'contenidos.banner_url as banner',
            )
            ->where('users.estado_id', 1)
            ->get();
        return view('socios.index', compact('results'));
    }
}
