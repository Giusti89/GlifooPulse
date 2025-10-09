<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'slug',
        'estado',
        'orden',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    // Un producto pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Un producto puede tener varias imágenes
    public function imagenes()
    {
        return $this->hasMany(ImagenProducto::class)
            ->orderBy('orden');
    }

    /*
    |--------------------------------------------------------------------------
    | Boot para manejar slug automáticamente
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        static::saving(function (Producto $producto) {
            if (empty($producto->slug)) {
                $producto->slug = Str::slug($producto->nombre);
            }

            // Evita duplicados dentro de la misma categoría
            $originalSlug = $producto->slug;
            $counter = 1;

            while (
                Producto::where('slug', $producto->slug)
                ->where('categoria_id', $producto->categoria_id)
                ->where('id', '!=', $producto->id ?? 0)
                ->exists()
            ) {
                $producto->slug = $originalSlug . '-' . $counter++;
            }
        });
    }
}
