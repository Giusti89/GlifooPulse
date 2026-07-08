<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Swindon\FilamentHashids\Traits\HasHashid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Spot extends Model
{
    use HasFactory;
    use HasHashid;

    protected $fillable = [
        'titulo',
        'slug',
        'tipolanding',
        'estado',
        'suscripcion_id',
        'contador',
    ];

    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class, 'suscripcion_id', 'id');
    }

    public function contenido()
    {
        return $this->hasOne(Contenido::class, 'spot_id', 'id');
    }

    public function seo()
    {
        return $this->hasOne(Seo::class);
    }
    public function socials()
    {
        return $this->hasMany(Social::class, 'spot_id', 'id');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class, 'spot_id', 'id');
    }
    public function videos()
    {
        return $this->hasMany(Video::class)->orderBy('orden', 'asc');
    }
    public function portfolios()
    {
        return $this->hasMany(Portfolio::class, 'spot_id', 'id');
    }
    public function horarios(): HasMany
    {
        return $this->hasMany(HorarioAtencion::class, 'spot_id')->orderBy('dia', 'asc');
    }
    // MÉTODOS
    public function incrementarVisita()
    {
        $this->increment('contador');
        $this->save();
    }
    protected static function booted()
    {
        static::creating(function ($spot) {
            if (empty($spot->slug)) {
                $user = $spot->suscripcion->user;

                $baseSlug = Str::slug($user->name . '-' . $user->lastname);
                $slug = $baseSlug;
                $counter = 1;

                while (Spot::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }

                $spot->slug = $slug;

                if (empty($spot->titulo)) {
                    $spot->titulo = $slug;
                }
            }
        });
    }


    public function scopePublicos(Builder $query): Builder
    {
        return $query
            ->where('estado', 1)
            ->whereHas('suscripcion', function ($q) {
                $q->whereDate('fecha_fin', '>=', now());
            });
    }
    public function obtenerEstadoActual(): array
    {
        $ahora = Carbon::now('America/La_Paz');
        $diaActual = $ahora->isoweekday();
        $horaActual = $ahora->format('H:i:s');
        $horarioHoy = $this->horarios->where('dia', $diaActual)->first();

        if (!$horarioHoy || $horarioHoy->esta_cerrado) {
            return ['abierto' => false, 'texto' => 'Cerrado hoy'];
        }

        if ($horarioHoy->apertura && $horarioHoy->cierre) {
            if ($horaActual >= $horarioHoy->apertura && $horaActual <= $horarioHoy->cierre) {
                return ['abierto' => true, 'texto' => 'Abierto ahora'];
            }
        }

        if ($horarioHoy->apertura_2 && $horarioHoy->cierre_2) {
            if ($horaActual >= $horarioHoy->apertura_2 && $horaActual <= $horarioHoy->cierre_2) {
                return ['abierto' => true, 'texto' => 'Abierto ahora'];
            }
        }

        return ['abierto' => false, 'texto' => 'Cerrado ahora'];
    }
}
