<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Swindon\FilamentHashids\Traits\HasHashid;
use Illuminate\Support\Facades\Storage;


class Contenido extends Model
{
    use HasFactory;
    use HasHashid;
    protected $fillable = [
        'banner_url',
        'texto',
        'pie',
        'logo_url',
        'spot_id',
        'background',
        'latitude',
        'longitude',
        'ctexto',
    ];
    
    public function spot()
    {
        return $this->belongsTo(Spot::class, 'spot_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($ticket) {

            if ($ticket->isDirty('banner_url') && $ticket->isDirty('logo_url')) {
                Storage::disk('public')->delete('/' . $ticket->getOriginal('banner_url'));
                Storage::disk('public')->delete('/' . $ticket->getOriginal('logo_url'));
            }
        });

        static::deleting(function ($ticket) {
            if ($ticket->isDirty('banner_url') && $ticket->isDirty('logo_url')) {
                Storage::disk('public')->delete($ticket->banner_url);
                Storage::disk('public')->delete($ticket->logo_url);
            }
        });
    }
}
