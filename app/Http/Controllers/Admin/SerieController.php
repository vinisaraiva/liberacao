<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Serie;
use Illuminate\Http\Request;

class SerieController extends Controller
{
    public function index()
    {
        $series = Serie::all();
        return view('admin.series.index', compact('series'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|unique:series,nome',
        ]);

        Serie::create($validated);

        return redirect()->route('admin.series.index')
            ->with('success', 'Série criada com sucesso.');
    }

    public function update(Request $request, Serie $serie)
    {
        $validated = $request->validate([
            'nome' => 'required|string|unique:series,nome,' . $serie->id,
        ]);

        $serie->update($validated);

        return redirect()->route('admin.series.index')
            ->with('success', 'Série atualizada com sucesso.');
    }

    public function destroy(Serie $serie)
    {
        $serie->delete();
        return redirect()->route('admin.series.index')
            ->with('success', 'Série removida com sucesso. O histórico foi preservado.');
    }
}
