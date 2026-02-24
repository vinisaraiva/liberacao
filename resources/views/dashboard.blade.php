<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Liberação</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col font-sans">
    <header class="bg-green-700 pt-8 pb-4 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-green-600 rounded-full blur-3xl opacity-20 -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-green-800 rounded-full blur-2xl opacity-30 -ml-24 -mb-24"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="bg-white rounded-2xl shadow-xl flex items-center justify-between p-2 md:p-4 border border-white/20">
                <a href="/dashboard" class="flex items-center gap-4 group">
                    <div class="bg-gray-50 p-2 rounded-xl group-hover:bg-white transition-colors duration-300">
                        <img src="{{ asset('images/logo-ifba.png') }}" alt="IFBA Logo" class="h-10 md:h-14 w-auto group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-black text-lg md:text-2xl leading-none tracking-tighter uppercase text-green-800">Sistema de Liberação</span>
                        <span class="text-[9px] md:text-[10px] text-green-600 font-bold uppercase tracking-[0.2em] mt-1">{{ \App\Models\Setting::get('campus_name', 'Campus Vitória da Conquista') }}</span>
                    </div>
                </a>

                <div class="flex items-center gap-2 md:gap-6">
                    <div class="text-right hidden sm:block border-r border-gray-100 pr-6">
                        <p class="font-black text-gray-800 uppercase text-xs tracking-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-green-600 font-black uppercase tracking-widest mt-0.5">{{ Auth::user()->role }}</p>
                    </div>
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 p-3 rounded-xl transition-all duration-300 shadow-sm group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto p-6 flex-grow">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm animate-pulse">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Stat Cards -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Liberações Hoje</p>
                <div class="flex items-end justify-between mt-2">
                    <p class="text-3xl font-black text-gray-900" id="stat-total">--</p>
                    <span class="text-xs text-gray-400">Total geral</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
                <p class="text-sm font-bold text-green-600 uppercase tracking-wider">Aguardando Saída</p>
                <div class="flex items-end justify-between mt-2">
                    <p class="text-3xl font-black text-green-600" id="stat-waiting">--</p>
                    <span class="text-xs text-green-400">Na portaria</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
                <p class="text-sm font-bold text-emerald-600 uppercase tracking-wider">Confirmadas</p>
                <div class="flex items-end justify-between mt-2">
                    <p class="text-3xl font-black text-emerald-600" id="stat-released">--</p>
                    <span class="text-xs text-emerald-400">Saída concluída</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar Actions -->
            <div class="lg:col-span-1 space-y-4">
                <a href="{{ route('releases.create') }}" class="group w-full bg-green-600 hover:bg-green-700 text-white font-bold py-5 px-6 rounded-2xl shadow-lg transition-all transform hover:-translate-y-1 flex items-center justify-center gap-3">
                    <div class="bg-white/20 p-2 rounded-lg group-hover:scale-110 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </div>
                    Nova Liberação
                </a>
                
                <a href="{{ route('chaves.create') }}" class="group w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-5 px-6 rounded-2xl shadow-lg transition-all transform hover:-translate-y-1 flex items-center justify-center gap-3">
                    <div class="bg-white/20 p-2 rounded-lg group-hover:scale-110 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                    </div>
                    Autorizar Chave
                </a>

                <button onclick="refreshAll()" class="w-full bg-white border-2 border-dashed border-gray-200 hover:border-green-300 hover:bg-green-50 text-gray-500 hover:text-green-600 py-3 rounded-2xl flex items-center justify-center gap-2 transition-all group active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    <span class="text-xs font-black uppercase tracking-widest">Atualizar Painel</span>
                </button>
                
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200">
                    <h3 class="font-black text-gray-800 text-xs uppercase mb-4 tracking-widest text-center border-b pb-2">Menu Rápido</h3>
                    <div class="space-y-1">
                        @if(in_array(Auth::user()->role, ['ADMIN', 'PORTARIA']))
                        <a href="{{ route('portaria') }}" target="_blank" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-xl transition group">
                            <span class="p-1 bg-gray-100 group-hover:bg-white rounded-md transition">🏘️</span>
                            HUB Portaria (Geral)
                        </a>
                        <div class="ml-4 pl-4 border-l border-gray-100 space-y-1">
                            <a href="{{ route('portaria.alunos') }}" target="_blank" class="flex items-center gap-3 px-3 py-2 text-xs font-medium text-gray-500 hover:text-green-600 rounded-lg transition">
                                <span>👤</span> Liberação de Alunos
                            </a>
                            <a href="{{ route('portaria.chaves') }}" target="_blank" class="flex items-center gap-3 px-3 py-2 text-xs font-medium text-gray-500 hover:text-blue-600 rounded-lg transition">
                                <span>🔑</span> Controle de Chaves
                            </a>
                        </div>
                        @endif
                        <a href="{{ route('releases.history') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-700 rounded-xl transition group">
                            <span class="p-1 bg-gray-100 group-hover:bg-white rounded-md transition">📜</span>
                            Histórico Geral
                        </a>
                        <a href="{{ route('chaves.history') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-gray-600 hover:bg-blue-50 hover:text-blue-700 rounded-xl transition group">
                            <span class="p-1 bg-gray-100 group-hover:bg-white rounded-md transition">📋</span>
                            Histórico de Chaves
                        </a>
                        @if(Auth::user()->role === 'ADMIN')
                        <a href="{{ route('admin.terminals.index') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-700 rounded-xl transition group">
                            <span class="p-1 bg-gray-100 group-hover:bg-white rounded-md transition">🖥️</span>
                            Gerenciar Terminais
                        </a>
                        <a href="{{ route('admin.courses.index') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-700 rounded-xl transition group">
                            <span class="p-1 bg-gray-100 group-hover:bg-white rounded-md transition">📚</span>
                            Cursos
                        </a>
                        <a href="{{ route('admin.laboratorios.index') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-gray-600 hover:bg-blue-50 hover:text-blue-700 rounded-xl transition group">
                            <span class="p-1 bg-gray-100 group-hover:bg-white rounded-md transition">🔬</span>
                            Laboratórios
                        </a>
                        <a href="{{ route('admin.series.index') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-700 rounded-xl transition group">
                            <span class="p-1 bg-gray-100 group-hover:bg-white rounded-md transition">🏷️</span>
                            Séries
                        </a>
                        <a href="{{ route('admin.turmas.index') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-700 rounded-xl transition group">
                            <span class="p-1 bg-gray-100 group-hover:bg-white rounded-md transition">👥</span>
                            Turmas
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-700 rounded-xl transition group">
                            <span class="p-1 bg-gray-100 group-hover:bg-white rounded-md transition">👤</span>
                            Usuários
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-700 rounded-xl transition group">
                            <span class="p-1 bg-gray-100 group-hover:bg-white rounded-md transition">⚙️</span>
                            Configurações
                        </a>
                        @endif
                        <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-700 rounded-xl transition group">
                            <span class="p-1 bg-gray-100 group-hover:bg-white rounded-md transition">📊</span>
                            Relatórios e Auditoria
                        </a>
                    </div>
                </div>
            </div>

            <!-- Active Releases Table -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b bg-gray-50/50 flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-black text-gray-800">Liberações Pendentes</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Alunos aguardando validação na portaria</p>
                        </div>
                        <div class="flex items-center gap-2">
                             <span class="flex h-3 w-3 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                            </span>
                            <span class="text-[10px] font-black text-green-600 uppercase tracking-tighter">Live Monitor</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50/50 text-gray-400 text-[10px] uppercase tracking-widest font-black">
                                    <th class="px-6 py-4 border-b">Aluno / Matrícula</th>
                                    <th class="px-6 py-4 border-b">Horário Solicitação</th>
                                    <th class="px-6 py-4 border-b">Motivo / Origem</th>
                                    <th class="px-6 py-4 border-b text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="active-releases-body" class="divide-y divide-gray-100">
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="animate-spin h-6 w-6 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            Sincronizando dados...
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        let isFetchingSummary = false;
        let isFetchingActive = false;

        function fetchSummary() {
            if (isFetchingSummary) return;
            isFetchingSummary = true;
            
            fetch('/api/release-requests?limit=50')
                .then(res => res.json())
                .then(data => {
                    const all = data.data || data;
                    const todayStr = new Date().toDateString();
                    const today = all.filter(r => new Date(r.created_at).toDateString() === todayStr);
                    
                    document.getElementById('stat-total').textContent = today.length;
                    document.getElementById('stat-waiting').textContent = today.filter(r => r.status === 'waiting').length;
                    document.getElementById('stat-released').textContent = today.filter(r => r.status === 'released').length;
                })
                .finally(() => { isFetchingSummary = false; });
        }

        function fetchActiveReleases() {
            if (isFetchingActive) return;
            isFetchingActive = true;

            fetch('/api/release-requests?status=waiting&limit=20')
                .then(res => res.json())
                .then(data => {
                    const releases = data.data || data;
                    const body = document.getElementById('active-releases-body');
                    
                    if (releases.length === 0) {
                        body.innerHTML = '<tr><td colspan="4" class="px-6 py-16 text-center text-gray-400"><p class="text-sm italic">Nenhuma liberação pendente no momento.</p></td></tr>';
                        return;
                    }

                    body.innerHTML = releases.map(req => `
                        <tr class="hover:bg-green-50/30 transition-colors">
                            <td class="px-6 py-5">
                                <div class="font-bold text-gray-800 uppercase text-sm">${req.student.nome}</div>
                                <div class="text-[10px] font-mono text-gray-500">${req.student.matricula}</div>
                            </td>
                            <td class="px-6 py-5 text-xs text-gray-600 font-medium">
                                <div class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    ${new Date(req.created_at).toLocaleTimeString('pt-BR', {hour: '2-digit', minute:'2-digit'})}
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700 uppercase">
                                    ${req.motivo}
                                </span>
                                <div class="text-[9px] text-gray-400 mt-1 uppercase truncate max-w-[150px]">${req.sector || 'Geral'}</div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <form action="/liberacoes/${req.id}/cancelar" method="POST" onsubmit="return confirm('Deseja realmente CANCELAR esta liberação?')">
                                    @csrf
                                    <button type="submit" class="bg-white border-2 border-red-100 hover:border-red-500 text-red-400 hover:text-red-600 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase transition-all">
                                        Cancelar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    `).join('');
                })
                .finally(() => { isFetchingActive = false; });
        }

        function refreshAll() {
            fetchActiveReleases();
            fetchSummary();
        }

        // Initial load
        refreshAll();

        // Safe interval polling (every 60s)
        setInterval(refreshAll, 60000);
    </script>
</body>
</html>
