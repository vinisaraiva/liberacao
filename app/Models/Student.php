<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($student) {
            if ($student->nome) {
                $student->nome_busca = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::ascii($student->nome));
            }
        });
    }

    protected $fillable = [
        'matricula',
        'nome',
        'nome_busca',
        'curso_id',
        'serie_id',
        'turma_id',
    ];

    public function curso()
    {
        return $this->belongsTo(Course::class , 'curso_id');
    }

    public function serie()
    {
        return $this->belongsTo(Serie::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function releaseRequests()
    {
        return $this->hasMany(ReleaseRequest::class);
    }
}
