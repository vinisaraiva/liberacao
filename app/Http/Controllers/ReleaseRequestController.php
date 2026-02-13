<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReleaseRequest;
use App\Models\Student;
use App\Services\ReleaseRequestService;
use Illuminate\Support\Facades\Auth;

class ReleaseRequestController extends Controller
{
    protected $releaseRequestService;

    public function __construct(ReleaseRequestService $releaseRequestService)
    {
        $this->releaseRequestService = $releaseRequestService;
    }

    public function create()
    {
        $courses = \App\Models\Course::all();
        $series = \App\Models\Serie::all();
        $turmas = \App\Models\Turma::all();

        return view('releases.new', compact('courses', 'series', 'turmas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricula' => 'required|string',
            'nome' => 'required|string',
            'curso_id' => 'required|exists:courses,id',
            'serie_id' => 'required|exists:series,id',
            'turma_id' => 'required|exists:turmas,id',
            'motivo' => 'required|string',
            'observacao' => 'nullable|string',
        ]);

        $studentService = app(\App\Services\StudentService::class);
        $student = $studentService->upsertOnDemand($validated);

        $user = Auth::user();

        $releaseRequest = $this->releaseRequestService->createGenericRequest($student, $user, $validated);

        return response()->json($releaseRequest, 201);
    }

    public function index(Request $request)
    {
        $query = ReleaseRequest::with('student', 'createdByUser')
            ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $status = $request->query('status');
            if (in_array($status, ['waiting', 'released', 'cancelled', 'expired'])) {
                $query->where('status', $status);
            }
        }

        $limit = $request->query('limit', 20);
        $requests = $query->paginate($limit);

        return response()->json($requests);
    }

    public function confirm(Request $request, $id)
    {
        $releaseRequest = ReleaseRequest::findOrFail($id);
        $terminal = $request->attributes->get('terminal');

        if (!$terminal) {
            return response()->json(['message' => 'Terminal não identificado'], 401);
        }

        try {
            $this->releaseRequestService->confirmRelease($releaseRequest, $terminal);
            return response()->json(['message' => 'Liberado com sucesso']);
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function cancel(Request $request, $id)
    {
        $releaseRequest = ReleaseRequest::findOrFail($id);
        $user = Auth::user();

        try {
            $this->releaseRequestService->cancelRelease($releaseRequest, $user);
            return back()->with('success', 'Liberação cancelada com sucesso.');
        }
        catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
