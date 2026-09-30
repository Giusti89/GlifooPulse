<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialClickDaily extends Model
{
    use HasFactory;
    protected $fillable = [
        'social_id',
        'date',
        'utm_source',
        'utm_campaign',
        'device_type',
        'clicks',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function social()
    {
        return $this->belongsTo(Social::class);
    }
}
