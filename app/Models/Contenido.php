<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Swindon\FilamentHashids\Traits\HasHashid;
use Illuminate\Support\Facades\Storage;


class Contenido extends Model
{
    use HasFactory;
    use HasHashid;
    protected $fillable = [
        'banner_url',
        'texto',
        'pie',
        'logo_url',
        'spot_id',
        'background',
        'latitude',
        'longitude',
        'ctexto',
        'colsecond',
        'phone',
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class, 'spot_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        // Antes de actualizar: borra sólo el archivo del campo que cambió
        static::updating(function ($model) {
            // Lista de columnas a controlar
            foreach (['banner_url', 'logo_url'] as $attribute) {
                if ($model->isDirty($attribute)) {
                    $original = $model->getOriginal($attribute);
                    if ($original && Storage::disk('public')->exists($original)) {
                        Storage::disk('public')->delete($original);
                    }
                }
            }
        });

        // Al borrar el registro: elimina ambos ficheros (si existen)
        static::deleting(function ($model) {
            foreach (['banner_url', 'logo_url'] as $attribute) {
                $path = $model->{$attribute};
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        });
    }
}
