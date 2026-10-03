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
        // 1. Buscamos el negocio (Spot) de forma rápida
        $spot = Spot::where('slug', $spotSlug)->firstOrFail();

        // 2. Buscamos el producto específico por su slug
        $producto = Producto::where('slug', $productSlug)->firstOrFail();

        // 3. Obtenemos la primera imagen en formato absoluto para los bots
        $imagenRelacion = $producto->imagenes->first();
        $imagenOg = $imagenRelacion && !empty($imagenRelacion->url)
            ? asset('storage/' . $imagenRelacion->url)
            : asset('img/default-product.jpg');

        $descripcionLimpia = strip_tags(html_entity_decode($producto->descripcion, ENT_QUOTES, 'UTF-8'));
        $descripcionAcortada = Str::limit($descripcionLimpia, 150, '...');


        // 4. Preparamos la data estructurada de los Meta Tags
        $meta = [
            
            'titulo' => e($producto->nombre . " | " . $spot->titulo),
            'descripcion' => e($descripcionAcortada),
            'imagen' => $imagenOg,
            'url_destino' => route('publicidad', ['slug' => $spot->slug]) . "?prod=" . $producto->slug . "#prod-" . $producto->slug
        ];

        // 5. Retornamos una vista intermedia ultra-ligera diseñada EXCLUSIVAMENTE para los bots
        return view('share.producto', compact('meta'));
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
