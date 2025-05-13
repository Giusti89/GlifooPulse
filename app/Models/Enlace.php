<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Enlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'logo_path',       
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($ticket) {

            if ($ticket->isDirty('logo_path')) {
                Storage::disk('public')->delete('/' . $ticket->getOriginal('logo_path'));
            }
        });

        static::deleting(function ($ticket) {
            Storage::disk('public')->delete($ticket->logo_path);
           
        });
    }

}
