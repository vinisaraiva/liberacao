<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laboratorio;
use Illuminate\Http\Request;

class LaboratorioController extends Controller
{
    public function index()
    {
        $laboratorios = Laboratorio::all();
        return view('admin.laboratorios.index', compact('laboratorios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:120|unique:laboratorios,nome',
            'bloco' => 'nullable|string|max:50',
            'localizacao' => 'nullable|string|max:120',
        ]);

        Laboratorio::create($validated);

        return redirect()->route('admin.laboratorios.index')
            ->with('success', 'Sala cadastrada com sucesso.');
    }

    public function update(Request $request, Laboratorio $laboratorio)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:120|unique:laboratorios,nome,' . $laboratorio->id,
            'bloco' => 'nullable|string|max:50',
            'localizacao' => 'nullable|string|max:120',
        ]);

        $laboratorio->update($validated);

        return redirect()->route('admin.laboratorios.index')
            ->with('success', 'Sala atualizada com sucesso.');
    }

    public function toggle(Laboratorio $laboratorio)
    {
        $laboratorio->ativo = !$laboratorio->ativo;
        $laboratorio->save();

        $status = $laboratorio->ativo ? 'ativada' : 'desativada';
        return redirect()->route('admin.laboratorios.index')
            ->with('success', "Sala {$status} com sucesso.");
    }

    public function destroy(Laboratorio $laboratorio)
    {
        // Se houver histórico, talvez seja melhor não permitir a exclusão física, 
        // mas as migrations não usam SoftDeletes para labs. 
        // Porém, como temos chaves estrangeiras, o DB impedirá se houver autorizações vinculadas.
        try {
            $laboratorio->delete();
            return redirect()->route('admin.laboratorios.index')
                ->with('success', 'Sala removida com sucesso.');
        }
        catch (\Exception $e) {
            return redirect()->route('admin.laboratorios.index')
                ->with('error', 'Não é possível remover uma sala que possui histórico de autorizações.');
        }
    }
}
