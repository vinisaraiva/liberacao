<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminais - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-green-700 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Admin - Sistema de Liberação</h1>
            <a href="/dashboard" class="text-green-200 hover:text-white">Voltar ao Dashboard</a>
        </div>
    </nav>

    <main class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Gerenciar Terminais (Portaria)</h2>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm" role="alert">
                <p>{{ session('success') }}</p>
                @if(str_contains(session('success'), 'Token:'))
                    <p class="mt-2 font-mono bg-white p-2 border border-green-200 rounded text-sm break-all">
                        {{ explode('Token: ', session('success'))[1] }}
                    </p>
                    <p class="text-xs text-green-600 mt-1 italic">Copie este token agora. Por segurança, ele não será exibido novamente.</p>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form to Create New Terminal -->
            <div class="bg-white p-6 rounded-lg shadow-md h-fit">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Novo Terminal</h3>
                <form action="{{ route('admin.terminals.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nome do Terminal</label>
                        <input type="text" name="name" id="name" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ex: Portaria Principal">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="allowed_ip">IP Permitido (Opcional)</label>
                        <input type="text" name="allowed_ip" id="allowed_ip" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ex: 192.168.1.50">
                        <p class="text-xs text-gray-500 mt-1">Deixe em branco para permitir qualquer IP (protegido apenas pelo token).</p>
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-200">
                        Cadastrar Terminal
                    </button>
                </form>
            </div>

            <!-- Terminals List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nome</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">IP</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($terminals as $terminal)
                                <tr>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <p class="text-gray-900 font-semibold">{{ $terminal->name }}</p>
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <p class="text-gray-600">{{ $terminal->allowed_ip ?? 'Qualquer' }}</p>
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight {{ $terminal->is_active ? 'text-green-900' : 'text-red-900' }}">
                                            <span aria-hidden class="absolute inset-0 {{ $terminal->is_active ? 'bg-green-200' : 'bg-red-200' }} opacity-50 rounded-full"></span>
                                            <span class="relative">{{ $terminal->is_active ? 'Ativo' : 'Inativo' }}</span>
                                        </span>
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <div class="flex items-center gap-3">
                                            <form action="{{ route('admin.terminals.token', $terminal) }}" method="POST" onsubmit="return confirm('Deseja resetar o token deste terminal? A conexão atual será interrompida.')">
                                                @csrf
                                                <button type="submit" class="text-orange-600 hover:text-orange-900 text-xs font-bold uppercase">Resetar Token</button>
                                            </form>
                                            <form action="{{ route('admin.terminals.destroy', $terminal) }}" method="POST" onsubmit="return confirm('Excluir terminal?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-xs font-bold uppercase">Excluir</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 border-b border-gray-200 bg-white text-center text-gray-500">
                                        Nenhum terminal cadastrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
