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
            ->join('users', 'spots.user_id', '=', 'users.id')
            ->select('spots.slug as spot_slug', 'users.name as user_name')
            ->get();
        
        return view('socios/index',compact('results'));
    }
}
