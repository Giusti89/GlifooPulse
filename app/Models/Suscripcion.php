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
        $ahora = now();

        if ($this->fecha_fin && $ahora->lt(Carbon::parse($this->fecha_fin))) {
            // La suscripción aún está activa → solo extiendo la fecha_fin
            $this->fecha_fin = Carbon::parse($this->fecha_fin)->addMonths($meses);
            $this->estado = 1;
        } else {
            // La suscripción ha vencido → reinicio desde ahora
            $this->fecha_inicio = $ahora;
            $this->fecha_fin = $ahora->copy()->addMonths($meses);
            $this->estado = 1;
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
        $fechaFin = Carbon::parse($this->fecha_fin)->startOfDay();
        $hoy = Carbon::now()->startOfDay();

        $dias = $hoy->diffInDays($fechaFin, false); // false = diferencia negativa si ya venció

        if ($dias < 0) {
            return [
                'dias' => $dias,
                'texto' => "Vencido hace " . abs($dias) . " días",
                'color' => 'danger'
            ];
        }

        $resultado = [
            'dias' => $dias,
            'texto' => "{$dias} días restantes",
            'color' => 'success'
        ];

        if ($dias === 0) {
            $resultado['texto'] = 'Vence hoy';
            $resultado['color'] = 'warning';
        } elseif ($dias === 1) {
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
