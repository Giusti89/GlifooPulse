<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Swindon\FilamentHashids\Traits\HasHashid;

class ConsultaProducto extends Model
{

    use HasHashid;
    protected $fillable = [
        'producto_id',
        'nombre',
        'telefono',
        'mensaje',
        'ip_usuario',
        'fecha_consulta',
    ];

    protected $casts = [
        'fecha_consulta' => 'datetime',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
