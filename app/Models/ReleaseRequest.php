<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleaseRequest extends Model
{
    protected $fillable = [
        'student_id',
        'created_by_user_id',
        'created_by_name_snapshot',
        'sector',
        'motivo',
        'observacao',
        'valid_until',
        'status',
        'confirmed_at',
        'confirmed_terminal_id',
        'confirmed_terminal_name_snapshot',
    ];

    protected $casts = [
        'valid_until' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class , 'created_by_user_id');
    }

    public function confirmedTerminal()
    {
        return $this->belongsTo(Terminal::class , 'confirmed_terminal_id');
    }
}
