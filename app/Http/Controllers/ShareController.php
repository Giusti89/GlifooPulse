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
        // 1. Validar existencia del negocio y producto de forma segura
        $spot = Spot::where('slug', $spotSlug)->firstOrFail();
        $producto = Producto::where('slug', $productSlug)->firstOrFail();

        // 2. Resolver la URL absoluta de la imagen del producto
        $imagenRelacion = $producto->imagenes->first();
        $imagenOg = $imagenRelacion && !empty($imagenRelacion->url)
            ? asset('storage/' . $imagenRelacion->url)
            : asset('img/default-product.jpg');

        // 3. Armar la URL de destino final para cuando un humano abra el enlace
        try {
            $urlDestino = route('publicidad', ['slug' => $spot->slug]) . "?prod=" . $producto->slug . "#prod-" . $producto->slug;
        } catch (\Exception $e) {
            // Respaldo si la ruta con nombre 'publicidad' no está registrada exactamente así
            $urlDestino = url('/' . $spot->slug . "?prod=" . $producto->slug . "#prod-" . $producto->slug);
        }

        // 4. Preparar la data estética para los Bots de Meta/WhatsApp
        $meta = [
            'titulo' => "⭐ " . mb_strtoupper($producto->nombre), // Inyectamos la estrella directamente al título
            'descripcion' => Str::limit($producto->descripcion, 140, '...'),
            'imagen' => $imagenOg,
            'url_destino' => $urlDestino
        ];

        // 5. Retornar la vista intermedia
        return view('share.producto', compact('meta'));
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
