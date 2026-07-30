<?php

namespace App\Http\Controllers;

use App\Models\Contenido;
use App\Models\Producto;
use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShareController extends Controller
{
    public function shareProducto($spotSlug, $productSlug)
    {
        $spot = Spot::where('slug', $spotSlug)->firstOrFail();

        $producto = Producto::where('slug', $productSlug)->firstOrFail();

        $imagenRelacion = $producto->imagenes->first();
        $imagenOg = $imagenRelacion && !empty($imagenRelacion->url)
            ? asset('storage/' . $imagenRelacion->url)
            : $this->getProductImage($producto, null);
        $meta = [
            'titulo' => $producto->nombre . " | " . $spot->titulo,
            'descripcion' => Str::limit($producto->descripcion, 150, '...'),
            'imagen' => $imagenOg,

            'url_destino' => route('publicidad', ['slug' => $spot->slug]) . "?prod=" . $producto->slug . "#prod-" . $producto->slug,

            'url_compartir' => route('producto.compartir.enlace', [
                'spot_slug' => $spot->slug,
                'product_slug' => $producto->slug
            ])
        ];
        return view('share.producto', compact('meta', 'spot', 'producto'));
    }

    /**
     * Obtener imagen del producto (fallbacks)
     */
    private function getProductImage($producto, $contenido)
    {
        // 1. Intentar obtener imagen del producto
        $imagenProducto = $producto->imagenes()->first();
        if ($imagenProducto && !empty($imagenProducto->url)) {
            return asset('storage/' . $imagenProducto->url);
        }

        // 2. Fallback: imagen del spot
        if ($contenido && !empty($contenido->banner_url)) {
            return asset('storage/' . $contenido->banner_url);
        }

        // 3. Fallback final: logo por defecto
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
