<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Social extends Model
{
    use HasFactory;
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
}
