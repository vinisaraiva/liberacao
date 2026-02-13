<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
    <nav class="bg-green-700 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Admin - Usuários</h1>
            <a href="/dashboard" class="text-green-200 hover:text-white font-medium">Dashboard</a>
        </div>
    </nav>

    <main class="container mx-auto p-6 max-w-6xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight">Gerenciar Usuários</h2>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Create User Form -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 h-fit">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Novo Usuário</h3>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nome Completo</label>
                        <input type="text" name="name" required class="w-full border rounded-lg py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ex: João da Silva">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Usuário (Login)</label>
                        <input type="text" name="username" required class="w-full border rounded-lg py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ex: joao.silva">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">SIAPE (Opcional)</label>
                        <input type="text" name="siape" class="w-full border rounded-lg py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ex: 1234567">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Papel / Perfil</label>
                        <select name="role" required class="w-full border rounded-lg py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="AUTORIZADOR">Autorizador</option>
                            <option value="PORTARIA">Portaria</option>
                            <option value="ADMIN">Administrador</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Senha</label>
                        <input type="password" name="password" required class="w-full border rounded-lg py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Mínimo 6 caracteres">
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition shadow-md">
                        CADASTRAR USUÁRIO
                    </button>
                </form>
            </div>

            <!-- Users List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr class="text-xs font-black text-gray-400 uppercase tracking-widest">
                                <th class="px-6 py-4">Usuário</th>
                                <th class="px-6 py-4">Perfil</th>
                                <th class="px-6 py-4 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500 font-mono">
                                            {{ $user->username }} 
                                            @if($user->siape)
                                                <span class="text-green-400 mx-1">|</span> SIAPE: {{ $user->siape }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-black uppercase tracking-tighter {{ $user->role === 'ADMIN' ? 'bg-red-100 text-red-700' : ($user->role === 'AUTORIZADOR' ? 'bg-green-100 text-green-700' : 'bg-emerald-100 text-emerald-700') }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-3">
                                            @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Deseja excluir este usuário?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600 text-[10px] font-black uppercase tracking-tighter">Excluir</button>
                                            </form>
                                            @else
                                            <span class="text-[10px] font-black uppercase text-gray-400 italic">Você</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
