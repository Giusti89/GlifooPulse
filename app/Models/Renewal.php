<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renewal extends Model
{
    use HasFactory;
    protected $fillable = [
        'suscripcion_id',
        'fecha',
        'meses',
        'estado',
    ];

    protected $casts = [
        'estado' => 'string', // O usa un enum si prefieres
    ];

    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class);
    }
}
