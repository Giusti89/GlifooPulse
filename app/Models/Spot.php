<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Swindon\FilamentHashids\Traits\HasHashid;
use Illuminate\Database\Eloquent\Builder;

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
}
