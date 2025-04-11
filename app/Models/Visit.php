<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    protected $fillable = [
        'spot_id',
        'ip',
        'user_agent',
        'visited_at'
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
