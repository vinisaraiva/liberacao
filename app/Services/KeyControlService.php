<?php

namespace App\Services;

use App\Models\AutorizacaoChave;
use App\Models\Laboratorio;
use App\Models\MovimentacaoChave;
use App\Models\Student;
use App\Models\User;
use App\Models\Terminal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class KeyControlService
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Cria uma nova autorização para retirada de chave.
     */
    public function criarAutorizacao(array $data, User $autorizador)
    {
        return DB::transaction(function () use ($data, $autorizador) {
            $student = Student::findOrFail($data['student_id']);
            $lab = Laboratorio::findOrFail($data['laboratorio_id']);

            // Regra: Não permitir se já houver autorização ATIVA para o mesmo lab/aluno
            $existente = AutorizacaoChave::where('student_id', $student->id)
                ->where('laboratorio_id', $lab->id)
                ->where('status', 'ATIVA')
                ->first();

            if ($existente) {
                throw new Exception("O aluno já possui uma autorização ativa para este laboratório.");
            }

            $autorizacao = AutorizacaoChave::create([
                'student_id' => $student->id,
                'laboratorio_id' => $lab->id,
                'autorizado_por_user_id' => $autorizador->id,
                'autorizado_por_nome_snapshot' => $autorizador->name,
                'valid_from' => $data['valid_from'] ?? now(),
                'valid_until' => $data['valid_until'],
                'observacao' => $data['observacao'] ?? null,
                'status' => 'ATIVA',
            ]);

            $this->auditService->log($autorizador, null, 'CRIAR_AUTORIZACAO_CHAVE', $autorizacao, [
                'aluno' => $student->nome,
                'laboratorio' => $lab->nome,
                'validade' => $autorizacao->valid_until->toDateTimeString()
            ]);

            return $autorizacao;
        });
    }

    /**
     * Cancela uma autorização ativa.
     */
    public function cancelarAutorizacao(AutorizacaoChave $autorizacao, User $user)
    {
        if ($autorizacao->status !== 'ATIVA') {
            throw new Exception("Somente autorizações ATIVAS podem ser canceladas.");
        }

        return DB::transaction(function () use ($autorizacao, $user) {
            $autorizacao->status = 'CANCELADA';
            $autorizacao->save();

            $this->auditService->log($user, null, 'CANCELAR_AUTORIZACAO_CHAVE', $autorizacao);

            return $autorizacao;
        });
    }

    /**
     * Registra a retirada da chave na portaria.
     */
    public function registrarRetirada(AutorizacaoChave $autorizacao, Terminal $terminal, ?string $observacao = null)
    {
        $autorizacao->checkExpiration();

        if ($autorizacao->status !== 'ATIVA') {
            throw new Exception("Esta autorização não está mais ativa.");
        }

        if ($autorizacao->isRetirada()) {
            throw new Exception("Esta chave já foi retirada e ainda não foi devolvida.");
        }

        return DB::transaction(function () use ($autorizacao, $terminal, $observacao) {
            $movimentacao = MovimentacaoChave::create([
                'autorizacao_id' => $autorizacao->id,
                'acao' => 'RETIRADA',
                'terminal_id' => $terminal->id,
                'terminal_name_snapshot' => $terminal->name,
                'realizado_em' => now(),
                'observacao' => $observacao,
            ]);

            $this->auditService->log(null, $terminal, 'RETIRADA_CHAVE', $autorizacao, [
                'movimentacao_id' => $movimentacao->id
            ]);

            return $movimentacao;
        });
    }

    /**
     * Registra a devolução da chave na portaria.
     */
    public function registrarDevolucao(AutorizacaoChave $autorizacao, Terminal $terminal, ?string $observacao = null)
    {
        if (!$autorizacao->isRetirada()) {
            throw new Exception("Não há registro de retirada pendente de devolução para esta autorização.");
        }

        return DB::transaction(function () use ($autorizacao, $terminal, $observacao) {
            $movimentacao = MovimentacaoChave::create([
                'autorizacao_id' => $autorizacao->id,
                'acao' => 'DEVOLUCAO',
                'terminal_id' => $terminal->id,
                'terminal_name_snapshot' => $terminal->name,
                'realizado_em' => now(),
                'observacao' => $observacao,
            ]);

            $this->auditService->log(null, $terminal, 'DEVOLUCAO_CHAVE', $autorizacao, [
                'movimentacao_id' => $movimentacao->id
            ]);

            return $movimentacao;
        });
    }
}
