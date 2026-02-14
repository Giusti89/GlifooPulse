<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialClicks extends Model
{
    use HasFactory;
    protected $fillable = [
        'social_id',
        'clicked_at',
        'ip',
        'user_agent',
    ];

    public function social()
    {
        return $this->belongsTo(Social::class);
    }
}
