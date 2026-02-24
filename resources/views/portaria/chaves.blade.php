<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosk Chaves - Sistema de Liberação</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .animate-pulse-slow { animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .7; } }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #374151; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #4b5563; }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col font-sans overflow-hidden">
    <header class="bg-green-700 pt-6 pb-4 shadow-2xl relative overflow-hidden shrink-0">
        <div class="absolute top-0 right-0 w-64 h-64 bg-green-600 rounded-full blur-3xl opacity-20 -mr-32 -mt-32"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="bg-white rounded-2xl shadow-xl flex items-center justify-between p-2 md:p-3 border border-white/20">
                <a href="{{ route('portaria') }}" class="flex items-center gap-3 group">
                    <div class="bg-gray-50 p-1.5 rounded-xl group-hover:bg-white transition-colors duration-300">
                        <img src="{{ asset('images/logo-ifba.png') }}" alt="IFBA Logo" class="h-8 md:h-10 w-auto group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="font-black text-sm md:text-lg leading-none tracking-tighter uppercase text-green-800">Controle de Chaves</span>
                        <span class="text-[8px] md:text-[9px] text-green-600 font-bold uppercase tracking-[0.2em] mt-0.5">{{ \App\Models\Setting::get('campus_name', 'Campus Vitória da Conquista') }}</span>
                    </div>
                </a>

                <div class="flex items-center gap-2 md:gap-4">
                    <div class="hidden sm:flex items-center bg-gray-50 px-4 py-1.5 rounded-xl border border-gray-100 font-mono text-green-700 font-bold text-sm" id="clock">
                        00:00:00
                    </div>
                    <a href="{{ route('portaria') }}" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 shadow-sm border border-red-100">
                        Sair
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow flex flex-col md:flex-row overflow-hidden">
        <!-- Sidebar: Blocks and Rooms - Increased width for many rooms -->
        <div class="w-full md:w-3/5 lg:w-2/3 border-r border-gray-800 flex flex-col bg-gray-900/50">
            <div class="p-6 border-b border-gray-800">
                <div class="relative">
                    <input type="text" id="sala-search" oninput="searchDebounce()" 
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl py-3 px-10 text-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-white placeholder-gray-500"
                        placeholder="Buscar Sala ou Bloco...">
                    <svg class="h-5 w-5 text-gray-500 absolute left-3.5 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </div>

            <div id="blocos-container" class="flex-grow overflow-y-auto p-6 space-y-8 custom-scrollbar">
                <!-- Blocks and Rooms will be rendered here -->
                <div class="animate-pulse flex flex-col gap-4">
                    <div class="h-4 bg-gray-800 rounded w-1/4"></div>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2">
                        <div class="h-14 bg-gray-800 rounded-xl"></div>
                        <div class="h-14 bg-gray-800 rounded-xl"></div>
                        <div class="h-14 bg-gray-800 rounded-xl"></div>
                        <div class="h-14 bg-gray-800 rounded-xl"></div>
                        <div class="h-14 bg-gray-800 rounded-xl"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Panel: Room Info, Authorized Users, Current Holder - Reduced width -->
        <div id="details-panel" class="hidden md:flex flex-col md:w-2/5 lg:w-1/3 bg-gray-900 border-l border-gray-800">
            <div class="p-10 flex flex-col items-center justify-center h-full text-center text-gray-600">
                <svg class="w-20 h-20 mb-4 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" /></svg>
                <h2 class="text-xl font-black uppercase tracking-widest">Selecione uma Sala</h2>
                <p class="text-sm">Clique em uma sala ao lado para gerenciar a chave</p>
            </div>
        </div>
    </main>

    <!-- Modal Observação -->
    <div id="obs-modal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700 w-full max-w-md shadow-2xl">
            <h3 id="modal-title" class="text-xl font-black mb-4 uppercase text-blue-400 font-sans">Registrar Ação</h3>
            <p id="modal-desc" class="text-gray-400 text-sm mb-4">Deseja adicionar alguma observação?</p>
            <input type="hidden" id="modal-auth-id">
            <input type="hidden" id="modal-action">
            <textarea id="modal-obs" rows="3" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 text-white focus:outline-none focus:ring-2 focus:ring-green-500 mb-6" placeholder="Opcional..."></textarea>
            <div class="flex gap-3">
                <button onclick="closeModal()" class="flex-grow bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 rounded-xl transition uppercase text-xs">Cancelar</button>
                <button onclick="confirmAction()" class="flex-grow bg-blue-600 hover:bg-blue-500 text-white font-black py-3 rounded-xl transition uppercase text-xs tracking-widest shadow-lg">Confirmar</button>
            </div>
        </div>
    </div>

    <script>
        let searchTimeout;
        let selectedSalaId = null;
        let blocksData = [];
        let terminalToken = localStorage.getItem('terminal_token') || new URLSearchParams(window.location.search).get('token');

        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleTimeString('pt-BR');
        }
        setInterval(updateClock, 1000);
        updateClock();

        function searchDebounce() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(fetchData, 500);
        }

        async function fetchData() {
            const search = document.getElementById('sala-search').value;
            const url = `/portaria/chaves/dados?search=${encodeURIComponent(search)}`;

            try {
                const response = await fetch(url, {
                    headers: { 'X-Terminal-Token': terminalToken || '' }
                });

                if (response.status === 401 || response.status === 403) {
                    handleAuthError();
                    return;
                }

                const data = await response.json();
                blocksData = data.blocos;
                renderBlocks();
                
                if (selectedSalaId) {
                    const sala = findSalaById(selectedSalaId);
                    if (sala) renderDetails(sala);
                }
            } catch (err) {
                console.error('Erro ao buscar dados:', err);
            }
        }

        function findSalaById(id) {
            for (const bloco of blocksData) {
                const sala = bloco.salas.find(s => s.id === id);
                if (sala) return sala;
            }
            return null;
        }

        function renderBlocks() {
            const container = document.getElementById('blocos-container');
            if (blocksData.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-600 py-10 italic">Nenhuma sala encontrada.</div>';
                return;
            }

            container.innerHTML = blocksData.map(bloco => `
                <div>
                    <h3 class="text-[10px] font-black text-green-500 uppercase tracking-[0.3em] mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]"></span>
                        ${bloco.bloco}
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2">
                        ${bloco.salas.map(sala => `
                            <button onclick="selectSala(${sala.id})" class="p-3 rounded-xl border text-left transition-all duration-300 transform active:scale-95 group relative ${selectedSalaId === sala.id ? 'bg-green-600 border-green-400 shadow-[0_0_20px_rgba(34,197,94,0.3)]' : 'bg-gray-800 border-gray-700 hover:border-gray-500'}">
                                ${sala.holder ? '<div class="absolute top-2 right-2 w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></div>' : ''}
                                <span class="block text-[12px] font-black uppercase tracking-tight leading-none ${selectedSalaId === sala.id ? 'text-white' : 'text-gray-200 group-hover:text-white'}">${sala.nome}</span>
                                <span class="block text-[9px] mt-1.5 font-bold uppercase tracking-widest ${selectedSalaId === sala.id ? 'text-green-200' : 'text-gray-500'}">${sala.holder ? 'Uso' : 'Livre'}</span>
                            </button>
                        `).join('')}
                    </div>
                </div>
            `).join('');
        }

        function selectSala(id) {
            selectedSalaId = id;
            renderBlocks();
            const sala = findSalaById(id);
            if (sala) renderDetails(sala);
        }

        function renderDetails(sala) {
            const panel = document.getElementById('details-panel');
            panel.classList.remove('hidden');
            panel.innerHTML = `
                <div class="p-8 border-b border-gray-800 flex justify-between items-start">
                    <div>
                        <span class="text-xs font-black text-gray-500 uppercase tracking-widest mb-1 block">${sala.bloco}</span>
                        <h2 class="text-4xl font-black uppercase tracking-tighter text-white mb-2">${sala.nome}</h2>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border ${sala.holder ? 'bg-amber-900/20 text-amber-500 border-amber-500/20' : 'bg-green-900/20 text-green-500 border-green-500/20'}">
                                ${sala.holder ? 'Chave Retirada' : 'Chave na Portaria'}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex-grow overflow-y-auto p-8 custom-scrollbar">
                    ${sala.holder ? `
                        <div class="mb-10">
                            <h3 class="text-xs font-black text-red-500 uppercase tracking-widest mb-4">Atual Detentor</h3>
                            <div class="bg-red-900/10 border border-red-500/20 p-6 rounded-2xl flex justify-between items-center group">
                                <div>
                                    <h4 class="text-2xl font-black text-red-500 uppercase tracking-tight">${sala.holder.student_name}</h4>
                                    <p class="text-[10px] text-red-400/60 uppercase font-black tracking-widest mt-1">Matrícula: ${sala.holder.student_matricula}</p>
                                </div>
                                <button onclick="openModal(${sala.holder.id}, 'devolver')" class="bg-red-600 hover:bg-red-500 text-white px-8 py-3 rounded-xl font-black uppercase text-xs tracking-widest transition shadow-lg active:scale-95">
                                    Devolver
                                </button>
                            </div>
                        </div>
                    ` : ''}

                    <div>
                        <h3 class="text-xs font-black text-blue-500 uppercase tracking-widest mb-4">Pessoas Autorizadas</h3>
                        <div class="grid grid-cols-1 gap-4">
                            ${sala.authorized.filter(a => !a.is_retirada).map(auth => `
                                <div class="bg-gray-800/50 border border-gray-700/50 p-6 rounded-2xl flex justify-between items-center hover:border-blue-500/30 transition-colors">
                                    <div>
                                        <h4 class="text-xl font-black text-white uppercase tracking-tight">${auth.student_name}</h4>
                                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest mt-1">
                                            Válido até ${auth.validade} • Matrícula: ${auth.student_matricula}
                                        </p>
                                    </div>
                                    <button onclick="openModal(${auth.id}, 'retirar')" class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-3 rounded-xl font-black uppercase text-xs tracking-widest transition shadow-lg active:scale-95">
                                        Entregar
                                    </button>
                                </div>
                            `).join('')}
                            ${sala.authorized.filter(a => !a.is_retirada).length === 0 ? `
                                <div class="text-center py-10 text-gray-600 italic border border-dashed border-gray-800 rounded-2xl">
                                    Nenhuma solicitação ativa para esta sala.
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        }

        function openModal(id, action) {
            document.getElementById('modal-auth-id').value = id;
            document.getElementById('modal-action').value = action;
            document.getElementById('modal-title').innerText = action === 'retirar' ? 'Confirmar Retirada' : 'Confirmar Devolução';
            document.getElementById('modal-desc').innerText = action === 'retirar' ? 'Entregar a chave para o aluno selecionado?' : 'Registrar devolução da chave?';
            document.getElementById('modal-obs').value = '';
            document.getElementById('obs-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('obs-modal').classList.add('hidden');
        }

        async function confirmAction() {
            const id = document.getElementById('modal-auth-id').value;
            const action = document.getElementById('modal-action').value;
            const obs = document.getElementById('modal-obs').value;

            try {
                const response = await fetch(`/portaria/chaves/autorizacoes/${id}/${action}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Terminal-Token': terminalToken
                    },
                    body: JSON.stringify({ observacao: obs })
                });

                if (response.ok) {
                    closeModal();
                    fetchData();
                } else {
                    const result = await response.json();
                    alert('Erro: ' + (result.message || 'Falha ao processar.'));
                }
            } catch (err) {
                alert('Erro de conexão.');
            }
        }

        function handleAuthError() {
            alert('Erro de autenticação do terminal.');
            localStorage.removeItem('terminal_token');
            terminalToken = null;
            location.reload();
        }

        fetchData();
        setInterval(fetchData, 20000); // Polling 20s
    </script>
</body>
</html>
