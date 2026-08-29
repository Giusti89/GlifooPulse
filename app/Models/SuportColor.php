<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Swindon\FilamentHashids\Traits\HasHashid;

class SuportColor extends Model
{
    use HasFactory;
    use HasHashid;


    protected $fillable = [
        'spot_id',
        'fondocolor',
        'secondary',
        'text',
        'primary_button',
        'button_text',
        'header',
        'footer',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    // ========== RELACIONES ==========
    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }

    // ========== HELPERS ==========

    /**
     * Obtener colores para un spot específico
     * Si no existen, crearlos automáticamente
     */
    public static function getOrCreateForSpot($spotId): self
    {
        $colors = self::where('spot_id', $spotId)->first();

        if (!$colors) {
            $colors = self::create([
                'spot_id' => $spotId,
                'fondocolor' => '#ffffff',
                'secondary' => '#333333',
                'text' => '#333333',
            ]);
        }

        return $colors;
    }
}
