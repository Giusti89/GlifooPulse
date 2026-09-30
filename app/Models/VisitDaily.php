<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitDaily extends Model
{
    use HasFactory;
    protected $fillable = [
        'spot_id',
        'date',
        'utm_source',
        'utm_campaign',
        'referrer_domain',
        'device_type',
        'visits',
        'unique_visitors',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
