<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turma extends Model
{
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($turma) {
            $turma->students()->delete();
        });
    }

    protected $fillable = ['curso_id', 'serie_id', 'nome'];

    public function curso()
    {
        return $this->belongsTo(Course::class , 'curso_id');
    }

    public function serie()
    {
        return $this->belongsTo(Serie::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
