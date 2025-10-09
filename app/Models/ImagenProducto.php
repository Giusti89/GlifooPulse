<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ImagenProducto extends Model
{
    use HasFactory;

    protected $table = 'imagen_productos';

    protected $fillable = [
        'producto_id',
        'url',
        'orden',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($ticket) {

            if ($ticket->isDirty('url')) {
                Storage::disk('public')->delete('/' . $ticket->getOriginal('url'));
            }
        });

        static::deleting(function ($imagen) {
            if ($imagen->url && Storage::disk('public')->exists($imagen->url)) {
                Storage::disk('public')->delete($imagen->url);
            }
        });
    }
}
