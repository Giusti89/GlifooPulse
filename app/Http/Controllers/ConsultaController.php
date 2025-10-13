<?php

namespace App\Http\Controllers;

use App\Models\ConsultaProducto;
use App\Models\Producto;
use Illuminate\Http\Request;

use Filament\Notifications\Notification;


class ConsultaController extends Controller
{
    public function store(Request $request, $productoId)
    {
        $producto = Producto::findOrFail($productoId);

        $validated = $request->validate([
            'nombre'   => ['nullable', 'string', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'mensaje'  => ['required', 'string', 'max:500'],
        ]);
       

        ConsultaProducto::create([
            'producto_id'   => $producto->id,
            'nombre'        => $validated['nombre'] ?? null,
            'telefono'      => $validated['telefono'] ?? null,
            'mensaje'       => $validated['mensaje'],
            'ip_usuario'    => $request->ip(),
            'fecha_consulta' => now(),
        ]);

        
        $whatsNumber = $producto->categoria->spot->contenido->phone ?? null;

        if (! $whatsNumber) {
            return back()->with('error', 'No hay número de WhatsApp configurado.');
        }

        
        $texto = sprintf(
            'Hola, me interesa el producto %s. %s',
            $producto->nombre,
            $validated['mensaje']
        );

        $url = 'https://wa.me/' . $whatsNumber . '?text=' . urlencode($texto);

        return redirect()->away($url);
    }
}
