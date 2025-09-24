<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipoproducto extends Model
{
    use HasFactory;
     protected $fillable = [
        'nombre',
        'detalle',
    ];

    public function paquetes()
    {
        return $this->hasMany(Paquete::class);
    }
}
