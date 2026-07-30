<?php

namespace App\Http\Controllers;

use App\Models\Contenido;
use App\Models\Producto;
use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShareController extends Controller
{
    public function shareProduct(Request $request, $slug)
    {
        $spot = Spot::where('slug', $slug)->firstOrFail();
        
        // Obtener el producto por slug
        $productSlug = $request->query('prod');
        if (!$productSlug) {
            return redirect()->route('spot.show', $slug);
        }

        $producto = Producto::whereHas('categoria', function($query) use ($spot) {
            $query->where('spot_id', $spot->id);
        })->where('slug', $productSlug)->firstOrFail();

        $contenido = Contenido::where('spot_id', $spot->id)->first();

        // Preparar metadatos optimizados para compartir
        $titulo = $producto->nombre . ' | ' . $spot->titulo;
        $descripcion = Str::limit($producto->descripcion ?? 'Descubre este producto en ' . $spot->titulo, 160);
    
        $imagen = $this->getProductImage($producto, $contenido);
    
        $url = route('spot.show', ['slug' => $slug]) . '?prod=' . $productSlug;

        // 🔥 Registrar que se compartió (opcional)
        // $this->registerShare($producto, $spot);
        dd($imagen);
        return view('layouts.share', compact(
            'spot',
            'producto',
            'contenido',
            'titulo',
            'descripcion',
            'imagen',
            'url'
        ));
    }

    private function getProductImage($producto, $contenido)
    {
        $imagenProducto = $producto->imagenes()->first();
        if ($imagenProducto && !empty($imagenProducto->url)) {
            return asset('storage/' . $imagenProducto->url);
        }
        
        if ($contenido && !empty($contenido->banner_url)) {
            return asset('storage/' . $contenido->banner_url);
        }

        return asset('img/logos/Boton.ico');
    }

    /**
     * Registrar estadísticas de compartidos (opcional)
     */
    private function registerShare($producto, $spot)
    {
        // Puedes crear una tabla 'shares' para registrar:
        // - producto_id
        // - spot_id
        // - ip
        // - user_agent
        // - fecha
        // Esto te ayudará a saber qué productos son más compartidos
    }
}
