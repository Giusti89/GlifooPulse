<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
     use HasFactory;
    protected $fillable = [
        'total',
        'pago',
        'fecha',
        'suscripcion_id',
        'estadov_id',
    ];

     public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class);
    }

    
    public function estadov()
    {
        return $this->belongsTo(Estadov::class);
    }
}
