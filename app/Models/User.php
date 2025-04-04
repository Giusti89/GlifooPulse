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

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    // rol
    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }
    // suscripcion
    public function suscripcion()
    {
        return $this->hasMany(Suscripcion::class);
    }
    // estado
    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }
    // spot
    public function spot()
    {
        return $this->hasMany(Spot::class);
    }
    // devolucion rol
    public function hasRole(string $role): bool
    {
        return $this->rol->nombre === $role;
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
}
