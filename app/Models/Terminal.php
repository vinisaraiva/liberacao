<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    protected $fillable = [
        'name',
        'token_hash',
        'allowed_ip',
        'is_active',
    ];

    protected $hidden = [
        'token_hash',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
