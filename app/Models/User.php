<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Swindon\FilamentHashids\Traits\HasHashid;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasHashid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
        'phone',
        'estado_id',
        'rol_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    /**
     * metodos
     * 
     */
    // devolucion rol
    public function hasRole(string $role): bool
    {
        return $this->rol->nombre === $role;
    }
    public function nombreRol(): string
    {
        return $this->rol?->nombre ?? 'Sin rol';
    }
    // verificar si tiene suscripcion
    public function tieneSuscripcionActiva(): bool
    {
        return $this->suscripcion()->where('estado', true)->exists();
    }
    public function getSuscripcionActiva(): ?Suscripcion
    {
        return $this->suscripcion()
            ->where('estado', true)
            ->latest()
            ->first();
    }

    public function tieneTipoproducto(string $nombreTipo): bool
    {
        return optional($this->paquete?->tipoproducto)->nombre === $nombreTipo;
    }

    public function landingsCompradas()
    {
        return $this->belongsToMany(Landing::class, 'landing_user_compras')
            ->withPivot('fecha_compra', 'precio')
            ->withTimestamps();
    }

    public function tienePaquete($tipoPaquete)
    {
        return $this->paquete && $this->paquete->nombre === $tipoPaquete;
    }

    //accesos de panel
    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->hasRole('Administrador General')) {
            return true;
        }

        if ($panel->getId() === 'usuario' && $this->hasRole('Usuario')) {
            return true;
        }
        if ($panel->getId() === 'catalogo' && $this->tieneTipoproducto('Catalogo')) {
            return true;
        }

        return false;
    }

    public function tieneAccesoALanding($landingId)
    {
        $landing = Landing::find($landingId);

        if (!$landing->pago) {
            return true; // Landings gratuitas son accesibles
        }

        return $this->landingsCompradas()->where('landing_id', $landingId)->exists();
    }
    /**
     * realciones
     */

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }

    public function suscripcion()
    {
        return $this->hasOne(Suscripcion::class);
    }
    public function spot()
    {
        return $this->hasOneThrough(
            Spot::class,
            Suscripcion::class,
            'user_id',
            'suscripcion_id',
            'id',
        );
    }

    public function paquete()
    {
        return $this->hasOneThrough(
            Paquete::class,
            Suscripcion::class,
            'user_id',
            'id',
            'id',
            'paquete_id'
        );
    }
}
