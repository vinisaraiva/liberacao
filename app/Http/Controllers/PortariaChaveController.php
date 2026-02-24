<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AutorizacaoChave;
use App\Models\Student;
use App\Services\KeyControlService;
use Carbon\Carbon;

class PortariaChaveController extends Controller
{
    protected $keyControlService;

    public function __construct(KeyControlService $keyControlService)
    {
        $this->keyControlService = $keyControlService;
    }

    /**
     * Exibe a tela da portaria para controle de chaves.
     */
    public function index()
    {
        return view('portaria.chaves');
    }

    /**
     * Busca dados para a portaria (Salas por Bloco).
     */
    public function getDados(Request $request)
    {
        $search = $request->query('search');

        // Buscar todas as Salas (Salas) ativas
        $salasQuery = \App\Models\Laboratorio::where('ativo', true);

        if ($search) {
            $salasQuery->where(function ($q) use ($search) {
                $q->where('nome', 'LIKE', "%{$search}%")
                    ->orWhere('bloco', 'LIKE', "%{$search}%")
                    ->orWhereHas('autorizacoes', function ($sq) use ($search) {
                    $sq->where('status', 'ATIVA')
                        ->whereHas('student', function ($ssq) use ($search) {
                        $ssq->where('nome', 'LIKE', "%{$search}%")
                            ->orWhere('matricula', 'LIKE', "%{$search}%");
                    }
                    );
                }
                );
            });
        }

        $salas = $salasQuery->orderBy('bloco')->orderBy('nome')->get();

        // Agrupar por bloco
        $grouped = $salas->groupBy(function ($sala) {
            return $sala->bloco ?: 'Bloco Não Definido';
        })->map(function ($salasPorBloco, $blocoNome) {
            return [
            'bloco' => $blocoNome,
            'salas' => $salasPorBloco->map(function ($sala) {
                    // Buscar autorizações ativas para esta sala
                    $autorizacoes = AutorizacaoChave::with('student')
                        ->where('laboratorio_id', $sala->id)
                        ->where('status', 'ATIVA')
                        ->orderBy('valid_until', 'ASC')
                        ->get();

                    // Quem está com a chave? (Autorização marcada como retirada mas não devolvida)
                    $movimentacaoAtual = $autorizacoes->filter(function ($auth) {
                            return $auth->isRetirada();
                        }
                        )->first();

                        return [
                        'id' => $sala->id,
                        'nome' => $sala->nome,
                        'bloco' => $sala->bloco,
                        'holder' => $movimentacaoAtual ? [
                        'id' => $movimentacaoAtual->id,
                        'student_name' => $movimentacaoAtual->student->nome,
                        'student_matricula' => $movimentacaoAtual->student->matricula
                        ] : null,
                        'authorized' => $autorizacoes->map(function ($auth) {
                            return [
                            'id' => $auth->id,
                            'student_name' => $auth->student->nome,
                            'student_matricula' => $auth->student->matricula,
                            'validade' => $auth->valid_until->format('H:i'),
                            'is_retirada' => $auth->isRetirada()
                            ];
                        }
                        )
                        ];
                    }
                    )
                    ];
                })->values();

        return response()->json([
            'blocos' => $grouped
        ]);
    }

    /**
     * Registra a retirada.
     */
    public function retirar(Request $request, $id)
    {
        $autorizacao = AutorizacaoChave::findOrFail($id);
        $terminal = $request->attributes->get('terminal');

        try {
            $this->keyControlService->registrarRetirada($autorizacao, $terminal, $request->observacao);
            return response()->json(['message' => 'Retirada registrada com sucesso!']);
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Registra a devolução.
     */
    public function devolver(Request $request, $id)
    {
        $autorizacao = AutorizacaoChave::findOrFail($id);
        $terminal = $request->attributes->get('terminal');

        try {
            $this->keyControlService->registrarDevolucao($autorizacao, $terminal, $request->observacao);
            return response()->json(['message' => 'Devolução registrada com sucesso!']);
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
