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
        return $this->belongsTo(Producto::class);
    }

    protected static function booted()
    {
        static::deleting(function ($imagen) {
            if ($imagen->url && Storage::disk('public')->exists($imagen->url)) {
                Storage::disk('public')->delete($imagen->url);
            }
        });
    }
}
