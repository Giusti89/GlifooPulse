<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;


class Sell extends Model
{
    use HasFactory;
    protected $fillable = [
        'total',
        'pago',
        'fecha',
        'suscripcion_id',
        'estadov_id',
        'concepto',
        'landing_id',
    ];
    //relaciones
    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class);
    }


    public function estadov()
    {
        return $this->belongsTo(Estadov::class);
    }
    public function landing()
    {
        return $this->belongsTo(Landing::class);
    }
    public function getUser()
    {
        return $this->suscripcion->user;
    }
    //metodos

    public function procesarSuscripcion()
    {
        $this->update([
            'pago' => $this->total,
            'estadov_id' => 2,
        ]);

        $this->suscripcion?->update(['estado' => '1']);

        Notification::make()
            ->title('Suscripción activada')
            ->success()
            ->send();
    }

    public function procesarRenovacion()
    {
        $this->update([
            'pago' => $this->total,
            'estadov_id' => 2,
        ]);

        $renewal = $this->suscripcion?->renewals()
            ->where('estado', 'pendiente')
            ->latest()
            ->first();

        if ($renewal) {
            $this->suscripcion->renovar($renewal->meses);
            $renewal->update(['estado' => '1']);
        }

        Notification::make()
            ->title('Renovación completada')
            ->success()
            ->send();
    }
    public function procesarPlantilla()
    {
        // 1. Marcar la venta como pagada
        $this->update([
            'pago'        => $this->total,
            'estadov_id'  => 2,
        ]);

        $user = $this->getUser();
        $user->landingsCompradas()->attach(
            $this->landing_id,
            [
                'fecha_compra' => now(),
                'precio'       => $this->total,
            ]
        );


        Notification::make()
            ->title('Plantilla comprada correctamente')
            ->success()
            ->send();
    }
}
