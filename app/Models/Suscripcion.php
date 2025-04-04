<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'paquete_id',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paquete()
    {
        return $this->belongsTo(Paquete::class);
    }
    public function spot()
    {
        return $this->hasOne(Spot::class, 'suscripcion_id', 'id');
    }

    public function sells()
    {
        return $this->hasMany(Sell::class);
    }

    public function setFechaFinAttribute($value)
    {
        if ($this->fecha_inicio && !$value) {
            $this->attributes['fecha_fin'] = Carbon::parse($this->fecha_inicio)
                ->addMonths(request()->input('meses_suscripcion', 1));
        } else {
            $this->attributes['fecha_fin'] = $value;
        }
    }
    public function renovar($meses)
    {
        $this->fecha_fin = Carbon::parse($this->fecha_fin)->addMonths($meses);
        $this->save();
    }
}
