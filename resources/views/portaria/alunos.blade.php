<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portaria - Sistema de Liberação</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .animate-pulse-slow { animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .7; } }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col">
    <header class="bg-green-700 pt-8 pb-4 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-green-600 rounded-full blur-3xl opacity-20 -mr-32 -mt-32"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="bg-white rounded-2xl shadow-xl flex items-center justify-between p-2 md:p-4 border border-white/20">
                <a href="{{ route('portaria') }}" class="flex items-center gap-4 group">
                    <div class="bg-gray-50 p-2 rounded-xl group-hover:bg-white transition-colors duration-300">
                        <img src="{{ asset('images/logo-ifba.png') }}" alt="IFBA Logo" class="h-10 md:h-12 w-auto group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="font-black text-lg md:text-xl leading-none tracking-tighter uppercase text-green-800">Liberação de Alunos</span>
                        <span class="text-[9px] md:text-[10px] text-green-600 font-bold uppercase tracking-[0.2em] mt-1">{{ \App\Models\Setting::get('campus_name', 'Campus Vitória da Conquista') }}</span>
                    </div>
                </a>

                <div class="flex items-center gap-2 md:gap-4">
                    <button onclick="fetchRequests()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-3 rounded-xl transition-all duration-300 shadow-sm group" title="Atualizar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                    <div class="hidden sm:flex items-center bg-gray-50 px-4 py-2 rounded-xl border border-gray-100 font-mono text-green-700 font-bold" id="clock">
                        00:00:00
                    </div>
                    <a href="{{ route('portaria') }}" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-300 shadow-sm border border-red-100">
                        Sair
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto p-4 flex-grow">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Waiting List -->
            <div class="lg:col-span-2">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2 text-green-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Aguardando Liberação
                </h2>
                <div id="waiting-list" class="space-y-4">
                    <!-- Cards injected by JS -->
                    <div class="text-center text-gray-500 py-10">Carregando solicitações...</div>
                </div>
            </div>

            <!-- Recent History -->
            <div>
                <h2 class="text-xl font-bold mb-4 text-gray-400 uppercase tracking-widest text-sm">Últimas Saídas</h2>
                <div id="history-list" class="space-y-2 opacity-70">
                    <!-- History injected by JS -->
                </div>
            </div>
        </div>
    </main>

    <script>
        const waitingList = document.getElementById('waiting-list');
        const historyList = document.getElementById('history-list');

        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleTimeString('pt-BR');
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Terminal Token - In a real kiosk, this might be pre-configured in .env or local storage
        let terminalToken = localStorage.getItem('terminal_token') || new URLSearchParams(window.location.search).get('token');

        if (!terminalToken) {
            console.warn('Terminal token not found. Use ?token=YOUR_TOKEN or set terminal_token in localStorage.');
        }

        function fetchRequests() {
            // Fetch waiting requests
            fetch('/api/release-requests?status=waiting')
                .then(res => res.json())
                .then(data => {
                    renderWaiting(data.data || data);
                })
                .catch(err => console.error('Error fetching waiting:', err));

            // Fetch released requests (history)
            fetch('/api/release-requests?status=released&limit=5')
                .then(res => res.json())
                .then(data => {
                    renderHistory(data.data || data);
                })
                .catch(err => console.error('Error fetching history:', err));
        }

        // Initial load
        fetchRequests();

        // Safe interval polling (every 60s)
        setInterval(fetchRequests, 60000);

        function renderWaiting(requests) {
            if (requests.length === 0) {
                waitingList.innerHTML = '<div class="bg-gray-800 p-8 rounded-lg text-center text-gray-500 border border-gray-700">Nenhuma solicitação pendente no momento.</div>';
                return;
            }

            waitingList.innerHTML = requests.map(req => `
                <div class="bg-gray-800 rounded-3xl p-6 md:p-8 border-l-8 border-green-500 shadow-2xl flex flex-col md:flex-row justify-between items-center gap-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <div class="flex-grow text-center md:text-left w-full">
                        <h3 class="text-4xl md:text-5xl font-black text-white mb-2 uppercase tracking-tighter leading-none">${req.student.nome}</h3>
                        <div class="flex flex-wrap justify-center md:justify-start gap-2 mb-4">
                            <span class="bg-gray-900 border border-gray-700 text-gray-400 px-3 py-1 rounded-xl text-sm font-mono tracking-tighter uppercase font-bold">Matrícula: ${req.student.matricula}</span>
                            <span class="bg-green-900/40 text-green-400 px-3 py-1 rounded-xl text-sm uppercase font-black border border-green-500/20 tracking-widest">${req.student.turma_id || 'TURMA GERAL'}</span>
                        </div>
                        <div class="bg-gray-900/30 p-4 rounded-2xl border border-gray-700/30">
                            <div class="text-base md:text-lg mb-1">
                                <span class="text-gray-500 uppercase font-black text-[10px] tracking-widest block mb-1">Autorizado por:</span> 
                                <span class="text-white font-black uppercase tracking-tight">${req.created_by_name_snapshot}</span>
                            </div>
                            <div class="text-base md:text-lg">
                                <span class="text-green-500/50 uppercase font-black text-[10px] tracking-widest block mb-1">Motivo da Saída:</span> 
                                <span class="text-green-400 font-black uppercase tracking-tight ring-2 ring-green-500/20 px-2 py-0.5 rounded-lg inline-block">${req.motivo}</span>
                            </div>
                            ${req.observacao ? `<p class="text-sm text-gray-500 mt-4 italic border-t border-gray-800 pt-3">"${req.observacao}"</p>` : ''}
                        </div>
                    </div>
                    <div class="w-full md:w-auto mt-4 md:mt-0">
                        <button onclick="confirmExit(${req.id})" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white py-6 md:py-8 px-12 md:px-14 rounded-3xl shadow-[0_20px_50px_rgba(16,185,129,0.3)] transform transition hover:scale-[1.02] active:scale-95 flex flex-col items-center group">
                            <span class="text-xs md:text-sm font-black opacity-60 uppercase tracking-[0.3em] mb-2 group-hover:opacity-100 transition-opacity">Confirmar</span>
                            <span class="text-3xl md:text-4xl font-black tracking-tighter">LIBERAR</span>
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function renderHistory(requests) {
            historyList.innerHTML = requests.map(req => `
                <div class="bg-gray-800/50 p-4 rounded-2xl border border-gray-800 flex justify-between items-center mb-2">
                    <div>
                        <div class="font-black text-white uppercase text-sm tracking-tight">${req.student.nome}</div>
                        <div class="text-[10px] text-gray-500 font-bold uppercase mt-1 tracking-widest">Saída: ${new Date(req.confirmed_at).toLocaleTimeString('pt-BR', {hour: '2-digit', minute:'2-digit'})}</div>
                    </div>
                    <div class="text-green-500 text-[10px] font-black uppercase tracking-widest px-2 py-1 bg-green-500/10 rounded-lg">Saída OK</div>
                </div>
            `).join('');
        }

        function confirmExit(id) {
            if(!confirm('Confirmar a saída deste aluno?')) return;

            if (!terminalToken) {
                terminalToken = prompt('Por favor, insira o Token do Terminal:');
                if (terminalToken) localStorage.setItem('terminal_token', terminalToken);
                else return;
            }

            fetch(`/api/release-requests/${id}/confirm`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Terminal-Token': terminalToken
                }
            })
            .then(res => {
                if (res.status === 401 || res.status === 403) {
                    res.json().then(data => {
                        alert(`Erro de Autenticação: ${data.message}`);
                        localStorage.removeItem('terminal_token');
                        terminalToken = null;
                    });
                    return;
                }
                return res.json();
            })
            .then(data => {
                if (data) fetchRequests();
            })
            .catch(err => alert('Erro ao confirmar saída'));
        }

        fetchRequests();
    </script>
</body>
</html>
