<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Swindon\FilamentHashids\Traits\HasHashid;

class Landing extends Model
{
    use HasFactory;
    use HasHashid;
    protected $fillable = [
        'nombre',
        'descripcion',
        'preview_url',
        'pago',
        'precio',
        'paquete_id',
        'grupo',
        'nombrecomercial',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($ticket) {

            if ($ticket->isDirty('preview_url')) {
                Storage::disk('public')->delete('/' . $ticket->getOriginal('preview_url'));
            }
        });

        static::deleting(function ($ticket) {
            Storage::disk('public')->delete($ticket->preview_url);
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'landing_user_compras')
            ->withPivot('fecha_compra', 'precio')
            ->withTimestamps();
    }

    public function paquete()
    {
        return $this->belongsTo(Paquete::class);
    }

    public function compradores()
    {
        return $this->belongsToMany(User::class, 'landing_user_compras')
            ->withPivot('fecha_compra', 'precio')
            ->withTimestamps();
    }

    public function landings()
    {
        return $this->hasMany(Landing::class, 'paquete_id');
    }
    public function enlaces()
    {
        return $this->belongsToMany(
            Enlace::class,
            'enlace_landing',
            'landing_id',
            'enlace_id'
        );
    }
}
