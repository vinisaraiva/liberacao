<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AutorizacaoChave;
use App\Models\Laboratorio;
use App\Models\Student;
use App\Services\KeyControlService;
use App\Services\StudentService;
use Illuminate\Support\Facades\Auth;

class AutorizacaoChaveController extends Controller
{
    protected $keyControlService;
    protected $studentService;

    public function __construct(KeyControlService $keyControlService, StudentService $studentService)
    {
        $this->keyControlService = $keyControlService;
        $this->studentService = $studentService;
    }

    /**
     * Exibe a tela de criação de autorização.
     */
    public function create()
    {
        $laboratorios = Laboratorio::where('ativo', true)->orderBy('nome')->get();
        $courses = \App\Models\Course::all();
        $series = \App\Models\Serie::all();
        $turmas = \App\Models\Turma::all();

        return view('chaves.autorizar', compact('laboratorios', 'courses', 'series', 'turmas'));
    }

    /**
     * Salva uma nova autorização.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricula' => 'required|string',
            'nome' => 'required|string',
            'curso_id' => 'required|exists:courses,id',
            'serie_id' => 'required|exists:series,id',
            'turma_id' => 'required|exists:turmas,id',
            'laboratorio_id' => 'required|exists:laboratorios,id',
            'valid_from' => 'nullable|date',
            'valid_until' => 'required|date',
            'observacao' => 'nullable|string|max:255',
        ]);

        try {
            // Upsert do aluno por demanda
            $student = $this->studentService->upsertOnDemand($request->only([
                'matricula', 'nome', 'curso_id', 'serie_id', 'turma_id'
            ]));

            $autorizacaoData = array_merge($validated, ['student_id' => $student->id]);
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $autorizacao = $this->keyControlService->criarAutorizacao($autorizacaoData, $user);

            return response()->json([
                'message' => 'Autorização criada com sucesso!',
                'autorizacao' => $autorizacao
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Cancela uma autorização.
     */
    public function cancel(Request $request, $id)
    {
        $autorizacao = AutorizacaoChave::findOrFail($id);

        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $this->keyControlService->cancelarAutorizacao($autorizacao, $user);
            return back()->with('success', 'Autorização cancelada com sucesso.');
        }
        catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Exibe o histórico do módulo.
     */
    public function history(Request $request)
    {
        $query = AutorizacaoChave::with(['student', 'laboratorio', 'movimentacoes', 'autorizador'])
            ->orderBy('created_at', 'desc');

        // Filtros (Simplificados para MVP)
        if ($request->filled('laboratorio_id')) {
            $query->where('laboratorio_id', $request->laboratorio_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $autorizacoes = $query->paginate(20);
        $laboratorios = Laboratorio::orderBy('nome')->get();

        return view('chaves.historico', compact('autorizacoes', 'laboratorios'));
    }
}
