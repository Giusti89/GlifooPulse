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
        'visited_at',
        'referrer',
        'referrer_domain',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'device_type',
        'session_id',
    ];
    protected $casts = [
        'visited_at' => 'datetime',
    ];
    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
