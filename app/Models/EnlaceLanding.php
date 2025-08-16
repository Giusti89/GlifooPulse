<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnlaceLanding extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'landing_id',
        'enlace_id',
    ];

    // Relaciones para facilitar el uso en Filament
    public function landing()
    {
        return $this->belongsTo(Landing::class);
    }

    public function enlace()
    {
        return $this->belongsTo(Enlace::class);
    }
}
