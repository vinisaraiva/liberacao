<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratorio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'bloco',
        'localizacao',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * Retorna as autorizações de chave vinculadas a este laboratório.
     */
    public function autorizacoes()
    {
        return $this->hasMany(AutorizacaoChave::class);
    }
}
