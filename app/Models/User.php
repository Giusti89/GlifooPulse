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
     * *
     * *
     * *
     * 
     */
    // devolucion rol
    public function hasRole(string $role): bool
    {
        return $this->rol->nombre === $role;
    }

    // verificar si tiene suscripcion
    public function tieneSuscripcionActiva(): bool
    {
        return $this->suscripcion()->where('estado', true)->exists();
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
        return false;
    }
   /**
    * realciones
    *
    *
    *

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
        return $this->hasOne(Suscripcion::class);;
    }
    public function spot()
    {
        return $this->hasOneThrough(
            Spot::class,
            Suscripcion::class,
            'user_id',     
            'suscripcion_id',
            'id',          
            'id'           
        );
    }
   
}
