<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contenido extends Model
{
    use HasFactory;
    protected $fillable = [
        'banner_url',
        'texto',
        'pie',
        'logo_url',
        'spot_id',
        
    ];
    public function spot()
    {
        return $this->belongsTo(Spot::class, 'spot_id', 'id');
    }
}
