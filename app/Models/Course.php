<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($course) {
            $course->turmas()->delete();
            $course->students()->delete();
        });
    }

    protected $fillable = ['nome'];

    public function turmas()
    {
        return $this->hasMany(Turma::class , 'curso_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class , 'curso_id');
    }
}
