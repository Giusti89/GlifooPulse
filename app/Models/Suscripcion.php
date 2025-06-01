<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Swindon\FilamentHashids\Traits\HasHashid;

class Suscripcion extends Model
{
    use HasFactory;
    use HasHashid;

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
        return $this->hasMany(Spot::class);
    }
    
    public function renewals()
    {
        return $this->hasMany(Renewal::class);
    }

    public function sells()
    {
        return $this->hasMany(Sell::class);
    }

    public function tieneSuscripcionActiva()
    {
        return optional($this->suscripcion)->estado === true;
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

    public function renovar(int $meses)
    {
        $fechaActual = now();
        $fechaFinActual = Carbon::parse($this->fecha_fin);

        if ($fechaFinActual->isFuture()) {
            
            $this->fecha_fin = $fechaFinActual->copy()->addMonths($meses);
        } else {
            
            $this->fecha_fin = $fechaActual->copy()->addMonths($meses);
        }


        $this->save();
    }

    protected static function booted()
    {
        static::deleting(function ($suscripcion) {

            $spots = Spot::where('suscripcion_id', $suscripcion->id)->get();

            foreach ($spots as $spot) {

                $contenidos = Contenido::where('spot_id', $spot->id)->get();
                foreach ($contenidos as $contenido) {
                    if ($contenido->logo_url) {
                        Storage::disk('public')->delete('/' . ltrim($contenido->logo_url, '/'));
                    }
                    if ($contenido->banner_url) {
                        Storage::disk('public')->delete('/' . ltrim($contenido->banner_url, '/'));
                    }
                    $contenido->delete();
                }


                $socials = Social::where('spot_id', $spot->id)->get();
                foreach ($socials as $social) {
                    if ($social->image_url) {
                        Storage::disk('public')->delete('/' . ltrim($social->image_url, '/'));
                    }
                    $social->delete();
                }


                $spot->delete();
            }
        });
    }

    public function diasRestantes()
    {
        $fechaFin = Carbon::parse($this->fecha_fin);
        $hoy = Carbon::now();

        if ($hoy->gt($fechaFin)) {
            $diasPasados = $hoy->diffInDays($fechaFin);
            return [
                'dias' => -$diasPasados,
                'texto' => "Vencido hace {$diasPasados} días",
                'color' => 'danger'
            ];
        }

        $dias = $hoy->diffInDays($fechaFin);

        $resultado = [
            'dias' => $dias,
            'texto' => "{$dias} días restantes",
            'color' => 'success'
        ];

        if ($dias == 0) {
            $resultado['texto'] = 'Vence hoy';
            $resultado['color'] = 'warning';
        } elseif ($dias == 1) {
            $resultado['texto'] = 'Vence mañana';
            $resultado['color'] = 'warning';
        } elseif ($dias <= 7) {
            $resultado['color'] = 'warning';
        }

        return $resultado;
    }

    public function getDiasRestantesTextoAttribute()
    {
        return $this->diasRestantes()['texto'];
    }

    public function getDiasRestantesColorAttribute()
    {
        return $this->diasRestantes()['color'];
    }
}
