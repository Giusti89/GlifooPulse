<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipored extends Model
{
    protected $fillable = ['nombre'];

    public function enlaces()
    {
        return $this->hasMany(Enlace::class);
    }

    public function socials()
    {
        return $this->hasMany(Social::class);
    }
}
