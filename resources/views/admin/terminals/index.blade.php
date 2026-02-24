<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminais - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <header class="bg-green-700 pt-8 pb-4 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-green-600 rounded-full blur-3xl opacity-20 -mr-32 -mt-32"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="bg-white rounded-2xl shadow-xl flex items-center justify-between p-2 md:p-4 border border-white/20">
                <a href="/dashboard" class="flex items-center gap-4 group">
                    <div class="bg-gray-50 p-2 rounded-xl group-hover:bg-white transition-colors duration-300">
                        <img src="{{ asset('images/logo-ifba.png') }}" alt="IFBA Logo" class="h-10 md:h-12 w-auto group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-black text-lg md:text-xl leading-none tracking-tighter uppercase text-green-800">Admin - Terminais</span>
                        <span class="text-[9px] md:text-[10px] text-green-600 font-bold uppercase tracking-[0.2em] mt-1">{{ \App\Models\Setting::get('campus_name', 'Campus Vitória da Conquista') }}</span>
                    </div>
                </a>

                <div class="flex items-center gap-6">
                    <a href="/dashboard" class="bg-green-50 hover:bg-green-100 text-green-700 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-300 shadow-sm">
                        Voltar ao Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

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
                                            <button onclick="openEditModal({{ $terminal->id }}, '{{ $terminal->name }}', '{{ $terminal->allowed_ip }}', {{ $terminal->is_active ? 'true' : 'false' }})" 
                                                class="text-amber-600 hover:text-amber-900 text-xs font-bold uppercase">
                                                Editar
                                            </button>
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

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="editModalContent">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold">Editar Terminal</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nome do Terminal</label>
                        <input type="text" name="name" id="edit_name" required 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">IP Permitido (Opcional)</label>
                        <input type="text" name="allowed_ip" id="edit_allowed_ip" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="w-4 h-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="edit_is_active" class="text-sm font-bold text-gray-700">Ativo</label>
                    </div>
                </div>
                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="closeEditModal()" 
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded transition-all uppercase text-xs">
                        Cancelar
                    </button>
                    <button type="submit" 
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-all shadow-md uppercase text-xs">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, name, allowed_ip, is_active) {
            const modal = document.getElementById('editModal');
            const content = document.getElementById('editModalContent');
            const form = document.getElementById('editForm');
            
            form.action = `/admin/terminals/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_allowed_ip').value = allowed_ip === 'Qualquer' ? '' : allowed_ip;
            document.getElementById('edit_is_active').checked = is_active;
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            const content = document.getElementById('editModalContent');
            
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeEditModal();
            }
        }
    </script>
</html>
