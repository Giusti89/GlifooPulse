<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Swindon\FilamentHashids\Traits\HasHashid;

class Video extends Model
{
    use HasHashid;

    protected $fillable = [
        'spot_id',
        'titulo',
        'url',
        'url_embed',
        'proveedor',
        'orden',
        'estado'
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
