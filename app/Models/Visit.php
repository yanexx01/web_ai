<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'visited_at',
        'url',
        'ip_address',
        'host_name',
        'user_agent',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];
}
