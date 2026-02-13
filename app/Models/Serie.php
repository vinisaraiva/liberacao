<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Serie extends Model
{
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($serie) {
            $serie->turmas()->delete();
            $serie->students()->delete();
        });
    }

    protected $fillable = ['nome', 'ordem'];

    public function turmas()
    {
        return $this->hasMany(Turma::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
