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
        'landing_id'
    ];

    public function suscripcion()
    {
        return $this->hasMany(suscripcion::class);
    }

    public function landing()
    {
        return $this->belongsTo(Landing::class);
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
