<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class HorarioAtencion extends Model
{
    use HasFactory;

     protected $fillable = [
        'spot_id',
        'dia',
        'apertura',
        'cierre',
        'esta_cerrado',
    ];

     //relaciones

     public function spot(): BelongsTo
    {
        return $this->belongsTo(Spot::class);
    }

    //METODOS
     protected function nombreDia(): Attribute
    {
        return Attribute::make(
            get: function () {
                $dias = [
                    1 => 'Lunes',
                    2 => 'Martes',
                    3 => 'Miércoles',
                    4 => 'Jueves',
                    5 => 'Viernes',
                    6 => 'Sábado',
                    7 => 'Domingo'
                ];
                return $dias[$this->dia] ?? 'Desconocido';
            }
        );
    }
}
