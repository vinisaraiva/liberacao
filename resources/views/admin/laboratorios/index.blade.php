<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salas - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
    <header class="bg-green-700 pt-8 pb-4 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-green-600 rounded-full blur-3xl opacity-20 -mr-32 -mt-32"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="bg-white rounded-2xl shadow-xl flex items-center justify-between p-2 md:p-4 border border-white/20">
                <a href="/dashboard" class="flex items-center gap-4 group">
                    <div class="bg-gray-50 p-2 rounded-xl group-hover:bg-white transition-colors duration-300">
                        <img src="{{ asset('images/logo-ifba.png') }}" alt="IFBA Logo" class="h-10 md:h-12 w-auto group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-black text-lg md:text-xl leading-none tracking-tighter uppercase text-green-800">Admin - Salas</span>
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

    <main class="container mx-auto p-6 max-w-5xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight">Gerenciar Salas</h2>
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 h-fit">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4 border-b pb-2">Nova Sala</h3>
                <form action="{{ route('admin.laboratorios.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nome</label>
                        <input type="text" name="nome" required class="w-full border rounded-lg py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ex: Informática 01">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Bloco</label>
                        <input type="text" name="bloco" class="w-full border rounded-lg py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ex: Bloco A">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Localização</label>
                        <input type="text" name="localizacao" class="w-full border rounded-lg py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ex: Sala 05">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition shadow-md">
                        CADASTRAR
                    </button>
                </form>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr class="text-xs font-black text-gray-400 uppercase tracking-widest">
                                <th class="px-6 py-4">Sala</th>
                                <th class="px-6 py-4">Bloco</th>
                                <th class="px-6 py-4">Localização</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($laboratorios as $lab)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-bold text-gray-800 uppercase text-sm">
                                        {{ $lab->nome }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 text-sm uppercase">
                                        {{ $lab->bloco ?: 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 text-sm uppercase">
                                        {{ $lab->localizacao ?: 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($lab->ativo)
                                            <span class="px-2 py-1 text-[10px] font-black bg-green-100 text-green-700 rounded-full uppercase tracking-tighter">Ativo</span>
                                        @else
                                            <span class="px-2 py-1 text-[10px] font-black bg-gray-100 text-gray-500 rounded-full uppercase tracking-tighter">Inativo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-3">
                                            <button onclick="openEditModal({{ $lab->id }}, '{{ $lab->nome }}', '{{ $lab->bloco }}', '{{ $lab->localizacao }}')" class="text-amber-500 hover:text-amber-700 text-[10px] font-black uppercase tracking-tighter">
                                                Editar
                                            </button>
                                            <form action="{{ route('admin.laboratorios.toggle', $lab) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-blue-500 hover:text-blue-700 text-[10px] font-black uppercase tracking-tighter">
                                                    {{ $lab->ativo ? 'Desativar' : 'Ativar' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.laboratorios.destroy', $lab) }}" method="POST" onsubmit="return confirm('Deseja excluir esta sala?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600 text-[10px] font-black uppercase tracking-tighter">Excluir</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Nenhuma sala cadastrada.</td>
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
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Editar Sala</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Nome</label>
                        <input type="text" name="nome" id="edit_nome" required 
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Bloco</label>
                        <input type="text" name="bloco" id="edit_bloco" 
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Localização</label>
                        <input type="text" name="localizacao" id="edit_localizacao" 
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>
                </div>
                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="closeEditModal()" 
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-black py-3 rounded-xl transition-all uppercase text-[10px] tracking-widest">
                        Cancelar
                    </button>
                    <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-black py-3 rounded-xl transition-all shadow-lg shadow-blue-500/30 uppercase text-[10px] tracking-widest">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, nome, bloco, localizacao) {
            const modal = document.getElementById('editModal');
            const content = document.getElementById('editModalContent');
            const form = document.getElementById('editForm');
            
            form.action = `/admin/laboratorios/${id}`;
            document.getElementById('edit_nome').value = nome;
            document.getElementById('edit_bloco').value = bloco === 'N/A' ? '' : bloco;
            document.getElementById('edit_localizacao').value = localizacao === 'N/A' ? '' : localizacao;
            
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

        // Close modal on click outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeEditModal();
            }
        }
    </script>
</html>
