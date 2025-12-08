<?php

namespace App\Http\Controllers;

use App\Models\Renewal;
use App\Models\Sell;
use App\Models\Suscripcion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\Renovacion;
use Illuminate\Support\Facades\Redirect;

class RenovacionController extends Controller
{
    public function create($renovacion)
    {
        try {
            $id = Crypt::decrypt($renovacion);
            $user = User::with('suscripcion.paquete')->findOrFail($id);

            $tieneRenovacionPendiente = Renewal::whereHas('suscripcion', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                ->where('estado', 'pendiente')
                ->exists();

            if (!$tieneRenovacionPendiente) {
                return view('resuscripcion.index', [
                    'isSubmitting' => false,
                    'user' => $user,
                    'sus' => $user,
                    'encryptedId' => $renovacion
                ]);
            } else {
                return Redirect::route('inicio')->with('msj', 'resusenviada');
            }
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404, 'Enlace inválido');
        }
    }

    public function store(Request $request, $renovacion)
    {
        $request->validate([
            'meses' => 'required|in:1,3,6,12'
        ]);

        try {
            $userId = Crypt::decrypt($renovacion);
            $user = User::with('suscripcion')->findOrFail($userId);

            $renovacion = Renewal::create([
                'suscripcion_id' =>  $user->suscripcion->id,
                'fecha' => now(),
                'meses' => $request->meses,
                'estado' => 'pendiente',
            ]);

            $cuenta = Sell::create([
                'suscripcion_id' => $user->suscripcion->id,
                'total' => number_format($user->suscripcion->paquete->precio * $request->meses, 2),
                'fecha' => now(),
                'concepto' => "renovacion",
            ]);

            $adminEmails = User::where('id', 1)->pluck('email')->toArray();
            if (!empty($adminEmails)) {
                Mail::to($adminEmails)->send(new Renovacion(
                    $user,
                    $user->suscripcion->paquete,
                    $request->meses
                ));
            }

            return Redirect::route('inicio')->with('msj', 'solievi');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar: ' . $e->getMessage());
        }
    }
}
