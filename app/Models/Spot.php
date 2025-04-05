<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    use HasFactory;

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
    
    public function contenidos()
    {
        return $this->hasMany(Contenido::class, 'spot_id', 'id');
    }
}
