<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plataforma_visit extends Model
{
    protected $fillable = [
        'url',
        'path',
        'ip_address',
        'user_agent',
        'referer',
        'session_id',
        'user_id',
    ];
}
