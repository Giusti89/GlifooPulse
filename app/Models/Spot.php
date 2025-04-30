<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Swindon\FilamentHashids\Traits\HasHashid;

class Spot extends Model
{
    use HasFactory;
    use HasHashid;

    protected $fillable = [
        'titulo',
        'slug',
        'tipolanding',
        'estado',
        'suscripcion_id',
    ];

    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class, 'suscripcion_id', 'id');
    }

    public function contenido()
    {
        return $this->hasMany(Contenido::class, 'spot_id', 'id');
    }
    public function socials()
    {
        return $this->hasMany(Social::class, 'spot_id', 'id');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class, 'spot_id', 'id');
    }

    
}
