<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'actor_user_id',
        'actor_terminal_id',
        'action',
        'entity_type',
        'entity_id',
        'meta_json',
    ];

    protected $casts = [
        'meta_json' => 'array',
    ];

    public function actorUser()
    {
        return $this->belongsTo(User::class , 'actor_user_id');
    }

    public function actorTerminal()
    {
        return $this->belongsTo(Terminal::class , 'actor_terminal_id');
    }
}
