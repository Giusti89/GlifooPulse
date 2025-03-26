<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landing extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'descripcion',       
    ];

    public function users(): HasMany
    {
        return $this->hasMany(Paquete::class);
    }
}
