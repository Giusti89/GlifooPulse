<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Swindon\FilamentHashids\Traits\HasHashid;

class Social extends Model
{
    use HasFactory;
    use HasHashid;

    protected $fillable = [
        'nombre',
        'url',
        'clicks',
        'spot_id',
        'image_url',
        'tipored_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($ticket) {

            if ($ticket->isDirty('image_url')) {
                Storage::disk('public')->delete('/' . $ticket->getOriginal('image_url'));
            }
        });

        static::deleting(function ($ticket) {
            Storage::disk('public')->delete($ticket->image_url);
        });
    }
    public function spot()
    {
        return $this->belongsTo(Spot::class, 'spot_id', 'id');
    }
    public function tipoRed()
    {
        return $this->belongsTo(Tipored::class, 'tipored_id');
    }
    public static function getLandingFromLocalId($id)
    {
        return $id ?? null;
    }

    public static function getBotonesDisponiblesPorUsuario($userId)
    {
        // Obtenemos el usuario
        $user = User::with(['suscripcion.paquete.landings'])->find($userId);

        if (!$user || !$user->suscripcion || !$user->suscripcion->paquete) {
            return collect(); // Ningún botón
        }

        // Landings gratuitas del paquete
        $landingsGratis = $user->suscripcion->paquete->landings()
            ->where('pago', false)
            ->pluck('id');

        // Landings compradas por el usuario
        $landingsCompradas = Landing::join('landing_user_compras', 'landings.id', '=', 'landing_user_compras.landing_id')
            ->where('landing_user_compras.user_id', $user->id)
            ->pluck('landings.id');

        // IDs de landings disponibles
        $landingsPermitidas = $landingsGratis->concat($landingsCompradas)->unique();

        // Ahora filtramos botones por esas landings
        return Enlace::join('enlace_landings', 'enlaces.id', '=', 'enlace_landings.enlace_id')
            ->whereIn('enlace_landings.landing_id', $landingsPermitidas)
            ->select('enlaces.id', 'enlaces.nombre', 'enlaces.logo_path')
            ->distinct()
            ->get();
    }

    protected static function getLandingFromSpot($spotId)
    {
        return Spot::with('suscripcion.landing')->find($spotId)?->suscripcion?->landing;
    }
}
