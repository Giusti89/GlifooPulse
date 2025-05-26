<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class estadov extends Model
{
    use HasFactory;

     protected $fillable = [
        'nombre',
       
    ];

     public function sells()
    {
        return $this->hasMany(Sell::class);
    }
}
