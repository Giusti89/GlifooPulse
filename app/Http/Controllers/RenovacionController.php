<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class RenovacionController extends Controller
{
     public function showForm()
    {
        $user = auth()->user();
        $suscripcion = $user->suscripcion;
        return view('renovacion.form', compact('suscripcion'));
    }

    public function processRenovation(Request $request)
    {
        $request->validate([
            'meses' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $suscripcion = $user->suscripcion;

        // Actualizar fecha_fin
        $suscripcion->update([
            'fecha_fin' => Carbon::parse($suscripcion->fecha_fin)->addMonths($request->meses),
        ]);

        // Opcional: Registrar la renovación en una tabla aparte
        // $user->renovaciones()->create([...]);

        return redirect()->route('dashboard')->with('success', '¡Suscripción renovada exitosamente!');
    }
}
