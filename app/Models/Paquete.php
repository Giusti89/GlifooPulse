<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Swindon\FilamentHashids\Traits\HasHashid;

class Paquete extends Model
{
    use HasFactory;
    use HasHashid;
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'duaracion',
        'image_url',
        'enlace',
        'estado',
        'marco',
        'max_productos',
        'max_redes_sociales',
        'tipo_estadisticas',
        'max_imagenes_producto',
        'max_categorias',
        'seo_level',
    ];

    public function suscripcion()
    {
        return $this->hasMany(suscripcion::class);
    }

    public function tipoproducto()
    {
        return $this->belongsTo(Tipoproducto::class, 'tipoproducto_id');
    }

    public function landings()
    {
        return $this->hasMany(Landing::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($ticket) {


            if ($ticket->isDirty('image_url')) {
                Storage::disk('public')->delete('/' . $ticket->getOriginal('image_url'));
            }
        });

        static::deleting(function ($ticket) {
            Storage::disk('public')->delete($ticket->image_url);
        });
    }
}
