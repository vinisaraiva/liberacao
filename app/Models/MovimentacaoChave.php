<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimentacaoChave extends Model
{
    use HasFactory;

    protected $table = 'movimentacoes_chave';

    // Desabilitar timestamps padrão pois usamos 'realizado_em' explicitamente
    public $timestamps = false;

    protected $fillable = [
        'autorizacao_id',
        'acao',
        'terminal_id',
        'terminal_name_snapshot',
        'realizado_em',
        'observacao',
    ];

    protected $casts = [
        'realizado_em' => 'datetime',
    ];

    public function autorizacao()
    {
        return $this->belongsTo(AutorizacaoChave::class , 'autorizacao_id');
    }

    public function terminal()
    {
        return $this->belongsTo(Terminal::class);
    }
}
