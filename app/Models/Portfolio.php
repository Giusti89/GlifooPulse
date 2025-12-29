<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Swindon\FilamentHashids\Traits\HasHashid;
use Illuminate\Support\Facades\Cache;

class Portfolio extends Model
{
    use HasHashid;

    protected $fillable = [
        'spot_id',
        'titulo',
        'descripcion',
        'portada',
        'estado',
        'orden',
        'video_url'
    ];

    //relaciones
    public function galeria()
    {
        return $this->hasMany(Portfolioitem::class)->orderBy('orden');
    }

    public function dato()
    {
        return $this->hasOne(Portfoliodatos::class);
    }

    //metodos

    public function getUrlEmbedAttribute()
    {
        if (!$this->video_url) return null;

        if (preg_match('/(?:v=|youtu\.be\/|embed\/)([a-zA-Z0-9_-]+)/', $this->video_url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
        return $this->video_url;
    }
    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }

    protected static function boot()
    {
        parent::boot();

        /**
         * Crear automáticamente PortfolioDato al crear un Portfolio
         */
        static::created(function ($portfolio) {
            Portfoliodatos::create([
                'portfolio_id' => $portfolio->id,
                'implicacion' => null,
                'tecnologias' => json_encode([]),
                'cliente' => null,
                'enlace_proyecto' => null
            ]);
        });

        /**
         * Eliminar PortfolioDato al eliminar Portfolio (cascade)
         */
        static::deleting(function ($portfolio) {
            // Eliminar el registro relacionado en PortfolioDato
            $portfolio->dato()->delete();

            // Tu código existente para eliminar archivos
            foreach (['portada'] as $attribute) {
                $path = $portfolio->{$attribute};
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        });

        /**
         * Eliminar imagen anterior al actualizar (tu código existente)
         */
        static::updating(function ($model) {
            foreach (['portada'] as $attribute) {
                if ($model->isDirty($attribute)) {
                    $original = $model->getOriginal($attribute);
                    if ($original && Storage::disk('public')->exists($original)) {
                        Storage::disk('public')->delete($original);
                    }
                }
            }
        });
    }

    public static function getCachedForSpot()
    {
        // Obtener el spot_id del usuario autenticado o de otra manera
        $spotId = auth()->user()->spot_id ?? null;

        if (!$spotId) {
            return Portfolio::pluck('titulo', 'id');
        }

        return Cache::remember('portfolios_spot_' . $spotId, 3600, function () use ($spotId) {
            return Portfolio::where('spot_id', $spotId)
                ->where('estado', 'activo') // Opcional: filtrar solo activos
                ->orderBy('orden')
                ->pluck('titulo', 'id')
                ->toArray();
        });
    }
}
