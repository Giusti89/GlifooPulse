<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Swindon\FilamentHashids\Traits\HasHashid;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class ImagenProducto extends Model
{
    use HasFactory;
    use HasHashid;

    protected $table = 'imagen_productos';

    protected $fillable = [
        'producto_id',
        'url',
        'orden',
    ];

    // ========== RELACIONES ==========
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    // ========== BOOT ==========
    protected static function boot()
    {
        parent::boot();

        // ✅ USAR booted() EN LUGAR DE boot() PARA EVENTOS
        static::booted();
    }

    // ========== EVENTOS ==========
    protected static function booted()
    {
        // 🔒 VALIDACIÓN AL CREAR
        static::creating(function ($imagenProducto) {
            $user = Auth::user();

            $suscripcion = $user->getSuscripcionActiva();

            // 1. Validación de suscripción activa
            if (! $suscripcion || $suscripcion->estado != 1) {
                throw ValidationException::withMessages([
                    'url' => 'Necesitas una suscripción activa para añadir imágenes.',
                ]);
            }

            $maxImagenes = $suscripcion->paquete?->max_imagenes_producto;

            // 2. Si el plan no tiene límite (null), saltamos la validación
            if ($maxImagenes === null) {
                return;
            }

            // 3. Conteo actual en la base de datos para este producto
            // ✅ CONTAR SOLO IMÁGENES QUE NO ESTÁN MARCADAS COMO ELIMINADAS (si usas soft deletes)
            $imagenesCount = static::where('producto_id', $imagenProducto->producto_id)
                ->when(method_exists(static::class, 'withTrashed'), function ($query) {
                    return $query->whereNull('deleted_at');
                })
                ->count();

            // 4. Si se excede el límite, bloqueamos la inserción en BD
            if ($imagenesCount >= $maxImagenes) {
                throw ValidationException::withMessages([
                    'url' => "Límite alcanzado. Tu plan permite un máximo de {$maxImagenes} imágenes por producto.",
                ]);
            }
        });

        // 🗑️ ELIMINAR ARCHIVO AL ACTUALIZAR
        static::updating(function ($imagenProducto) {
            if ($imagenProducto->isDirty('url')) {
                $oldUrl = $imagenProducto->getOriginal('url');
                if ($oldUrl && Storage::disk('public')->exists($oldUrl)) {
                    Storage::disk('public')->delete($oldUrl);
                }
            }
        });

        // 🗑️ ELIMINAR ARCHIVO AL ELIMINAR
        static::deleting(function ($imagenProducto) {
            if ($imagenProducto->url && Storage::disk('public')->exists($imagenProducto->url)) {
                Storage::disk('public')->delete($imagenProducto->url);
            }
        });
    }
}
