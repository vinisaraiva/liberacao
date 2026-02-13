<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Turma;
use App\Models\Course;
use App\Models\Serie;
use Illuminate\Http\Request;

class TurmaController extends Controller
{
    public function index()
    {
        $turmas = Turma::with(['course', 'serie'])->get();
        $courses = Course::all();
        $series = Serie::all();
        return view('admin.turmas.index', compact('turmas', 'courses', 'series'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'curso_id' => 'required|exists:courses,id',
            'serie_id' => 'required|exists:series,id',
            'nome' => 'required|string',
        ]);

        // Check uniqueness for the combination
        $exists = Turma::where('curso_id', $validated['curso_id'])
            ->where('serie_id', $validated['serie_id'])
            ->where('nome', $validated['nome'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Esta turma já está cadastrada para este curso e série.');
        }

        Turma::create($validated);

        return redirect()->route('admin.turmas.index')
            ->with('success', 'Turma criada com sucesso.');
    }

    public function update(Request $request, Turma $turma)
    {
        $validated = $request->validate([
            'curso_id' => 'required|exists:courses,id',
            'serie_id' => 'required|exists:series,id',
            'nome' => 'required|string',
        ]);

        $turma->update($validated);

        return redirect()->route('admin.turmas.index')
            ->with('success', 'Turma atualizada com sucesso.');
    }

    public function destroy(Turma $turma)
    {
        $turma->delete();
        return redirect()->route('admin.turmas.index')
            ->with('success', 'Turma removida com sucesso. O histórico foi preservado.');
    }
}
