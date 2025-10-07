<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'spot_id',
        'nombre',
        'slug',
        'descripcion',
        'orden',
    ];

    // Agregar esta propiedad para cache más inteligente
    protected $cacheTtl = 3600; // 1 hora en segundos

    // Relaciones
    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    // Scope para ordenar por 'orden' y luego por nombre
    public function scopeOrdered($query)
    {
        return $query->orderBy('orden', 'asc')->orderBy('nombre', 'asc');
    }

    // NUEVO: Scope específico para selects optimizados
    public function scopeForSelect($query)
    {
        return $query->select(['id', 'nombre', 'orden'])
            ->ordered();
    }

    // NUEVO: Método estático para obtener categorías cacheadas
    public static function getCachedForSpot(?int $spotId = null)
    {
        $spotId = $spotId ?? auth()->user()->spot?->id;

        if (!$spotId) {
            return collect();
        }

        $cacheKey = "categorias_spot_{$spotId}";

        return Cache::remember($cacheKey, (new static)->cacheTtl, function () use ($spotId) {
            return static::where('spot_id', $spotId)
                ->forSelect() 
                ->pluck('nombre', 'id')
                ->toArray();
        });
    }

    // Boot: generar slug único por spot antes de crear/guardar si no existe
    protected static function booted()
    {
        static::creating(function (Categoria $categoria) {
            if (empty($categoria->spot_id) && auth()->check()) {
                $categoria->spot_id = auth()->user()->spot?->id;
            }
        });

        static::saving(function (Categoria $categoria) {
            
            if (empty($categoria->slug) && !empty($categoria->nombre)) {
                $categoria->slug = static::makeUniqueSlug(
                    $categoria->nombre,
                    $categoria->spot_id,
                    $categoria->id ?? null
                );
            }

            // Normalizar slug siempre
            $categoria->slug = Str::slug($categoria->slug);
        });

        static::saved(function ($categoria) {
            static::clearSpotCache($categoria->spot_id);
        });

        static::deleted(function ($categoria) {
            static::clearSpotCache($categoria->spot_id);
        });
    }

    /**
     * Genera slug único dentro del mismo spot
     */
    public static function makeUniqueSlug(string $nombre, ?int $spotId = null, ?int $excludeId = null): string
    {
        $base = Str::slug($nombre);
        $slug = $base;
        $i = 1;

        while (static::query()
            ->when($spotId, fn($q) => $q->where('spot_id', $spotId))
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where('slug', $slug)
            ->exists()
        ) {
            $i++;
            $slug = $base . '-' . $i;
        }

        return $slug;
    }

    // NUEVO: Método para limpiar cache específico
    public static function clearSpotCache(?int $spotId = null)
    {
        $spotId = $spotId ?? auth()->user()->spot?->id;

        if ($spotId) {
            Cache::forget("categorias_spot_{$spotId}");
        }
    }

    // NUEVO: Método para forzar refresh del cache
    public static function refreshSpotCache(?int $spotId = null)
    {
        static::clearSpotCache($spotId);
        return static::getCachedForSpot($spotId);
    }
}
