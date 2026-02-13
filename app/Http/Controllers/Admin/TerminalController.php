<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TerminalController extends Controller
{
    public function index()
    {
        $terminals = Terminal::all();
        return view('admin.terminals.index', compact('terminals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:terminals,name',
            'allowed_ip' => 'nullable|ip',
        ]);

        $terminal = Terminal::create([
            'name' => $validated['name'],
            'token_hash' => Str::random(32),
            'allowed_ip' => $validated['allowed_ip'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.terminals.index')
            ->with('success', "Terminal criado com sucesso. Token: {$terminal->token_hash}");
    }

    public function update(Request $request, Terminal $terminal)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:terminals,name,' . $terminal->id,
            'allowed_ip' => 'nullable|ip',
            'is_active' => 'boolean',
        ]);

        $terminal->update($validated);

        return redirect()->route('admin.terminals.index')
            ->with('success', 'Terminal atualizado com sucesso.');
    }

    public function destroy(Terminal $terminal)
    {
        $terminal->delete();
        return redirect()->route('admin.terminals.index')
            ->with('success', 'Terminal excluído com sucesso.');
    }

    public function generateToken(Terminal $terminal)
    {
        $token = Str::random(32);
        $terminal->update(['token_hash' => $token]);

        return redirect()->route('admin.terminals.index')
            ->with('success', "Novo token gerado para {$terminal->name}: {$token}");
    }
}
