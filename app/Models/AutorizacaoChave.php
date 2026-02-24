<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AutorizacaoChave extends Model
{
    use HasFactory;

    protected $table = 'autorizacoes_chave';

    protected $fillable = [
        'student_id',
        'laboratorio_id',
        'autorizado_por_user_id',
        'autorizado_por_nome_snapshot',
        'valid_from',
        'valid_until',
        'observacao',
        'status',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];

    /**
     * Boot do model para lidar com lógica de status automático.
     */
    protected static function boot()
    {
        parent::boot();

        // Garante que o status seja atualizado para EXPIRADA se passar do tempo
        static::retrieved(function ($model) {
            $model->checkExpiration();
        });
    }

    /**
     * Verifica se a autorização expirou e atualiza o status se necessário.
     */
    public function checkExpiration()
    {
        if ($this->status === 'ATIVA' && $this->valid_until->isPast()) {
            $this->status = 'EXPIRADA';
            $this->save();
        }
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function laboratorio()
    {
        return $this->belongsTo(Laboratorio::class);
    }

    public function autorizador()
    {
        return $this->belongsTo(User::class , 'autorizado_por_user_id');
    }

    /**
     * Retorna as movimentações (retirada/devolução) desta autorização.
     */
    public function movimentacoes()
    {
        return $this->hasMany(MovimentacaoChave::class , 'autorizacao_id');
    }

    /**
     * Verifica se a chave está atualmente retirada (sem devolução).
     */
    public function isRetirada()
    {
        // Se a relação já estiver carregada (eager loading), usamos a coleção para evitar N+1
        if ($this->relationLoaded('movimentacoes')) {
            $ultimaMovimentacao = $this->movimentacoes->sortByDesc('realizado_em')->first();
        }
        else {
            $ultimaMovimentacao = $this->movimentacoes()->latest('realizado_em')->first();
        }

        return $ultimaMovimentacao && $ultimaMovimentacao->acao === 'RETIRADA';
    }
}
