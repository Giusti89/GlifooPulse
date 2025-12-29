<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Swindon\FilamentHashids\Traits\HasHashid;

class Portfolioitem extends Model
{
    use HasHashid;

    protected $fillable = [
        'portfolio_id',
        'titulo',
        'descripcion',
        'imagen',
        'orden',
    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}
