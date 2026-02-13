<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'siape' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'role' => 'required|in:ADMIN,AUTORIZADOR,PORTARIA',
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'siape' => $validated['siape'] ?? null,
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário criado com sucesso.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'siape' => 'nullable|string|max:20',
            'role' => 'required|in:ADMIN,AUTORIZADOR,PORTARIA',
            'password' => 'nullable|string|min:6',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'siape' => $validated['siape'] ?? $user->siape,
            'role' => $validated['role'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Você não pode excluir seu próprio usuário.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário excluído com sucesso.');
    }
}
