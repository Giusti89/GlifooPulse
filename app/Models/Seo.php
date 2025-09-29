<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Swindon\FilamentHashids\Traits\HasHashid;


class Seo extends Model
{

    use HasFactory;
    use HasHashid;

    protected $fillable = [
        'spot_id',
        'logo',
        'descripcion',
        'seo_title',
        'seo_descripcion',
        'seo_keyword',
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
