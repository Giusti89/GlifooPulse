<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Swindon\FilamentHashids\Traits\HasHashid;
use Illuminate\Support\Facades\Storage;


class Portfolioitem extends Model
{
    use HasHashid;

    protected $fillable = [
        'portfolio_id',
        'titulo',
        'descripcion',
        'imagen',
        'orden',
    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }

    protected static function boot()
    {
        parent::boot();

        // AL ACTUALIZAR: si cambia la imagen, elimina la anterior
        static::updating(function ($item) {

            if ($item->isDirty('imagen')) {
                $imagenAnterior = $item->getOriginal('imagen');

                // Elimina la imagen anterior si existe
                if ($imagenAnterior && Storage::disk('public')->exists($imagenAnterior)) {
                    Storage::disk('public')->delete($imagenAnterior);
                }
            }
        });

        static::deleting(function ($item) {
            if ($item->imagen && Storage::disk('public')->exists($item->imagen)) {
                Storage::disk('public')->delete($item->imagen);
            }
        });
    }
}
