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
    <nav class="bg-gray-800 p-4 shadow-lg border-b border-gray-700">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="font-bold text-2xl flex items-center gap-2 text-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" /></svg>
                PORTARIA - Controle de Saída
            </a>
            <div class="flex items-center gap-6">
                <div class="hidden xl:block bg-green-900/30 border border-green-500/20 px-4 py-2 rounded-lg text-xs">
                    <span class="text-green-400 font-bold uppercase tracking-widest">Dica:</span>
                    <span class="text-gray-400">Gere o token no menu "Gerenciar Terminais" do Administrador.</span>
                </div>
                <button onclick="fetchRequests()" class="bg-gray-700 hover:bg-gray-600 text-xs font-bold py-2 px-4 rounded border border-gray-600 transition active:scale-95">
                    🔄 ATUALIZAR
                </button>
                <div class="text-gray-400 text-sm font-mono" id="clock">--:--:--</div>
            </div>
        </div>
    </nav>

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
                <div class="bg-gray-800 rounded-lg p-6 border-l-4 border-green-500 shadow-lg flex flex-col md:flex-row justify-between items-center gap-4 animate-in fade-in slide-in-from-bottom-2 duration-500">
                    <div class="flex-grow text-center md:text-left">
                        <h3 class="text-3xl font-bold text-white mb-1 uppercase tracking-tight">${req.student.nome}</h3>
                        <div class="text-gray-400 mb-2">
                            <span class="bg-gray-700 px-2 py-1 rounded text-xs mr-2 font-mono">${req.student.matricula}</span>
                            <span class="bg-green-900/40 text-green-400 px-2 py-1 rounded text-xs uppercase font-bold border border-green-500/20">${req.student.turma_id || 'Turma N/A'}</span>
                        </div>
                        <div class="text-sm">
                            <span class="text-gray-500">Por:</span> <span class="text-gray-300 font-semibold">${req.created_by_name_snapshot}</span>
                            <span class="text-gray-500 mx-1">•</span>
                            <span class="text-gray-500">Motivo:</span> <span class="text-green-300 font-bold uppercase">${req.motivo}</span>
                        </div>
                         ${req.observacao ? `<p class="text-xs text-gray-500 mt-2 italic bg-gray-900/50 p-2 rounded">"${req.observacao}"</p>` : ''}
                    </div>
                    <div class="flex-shrink-0">
                        <button onclick="confirmExit(${req.id})" class="bg-emerald-600 hover:bg-emerald-500 text-white text-lg font-black py-4 px-8 rounded-lg shadow-lg transform transition hover:scale-105 active:scale-95 flex flex-col items-center">
                            <span class="text-[10px] opacity-75 uppercase tracking-widest mb-1">Confirmar</span>
                            <span>S A Í D A</span>
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function renderHistory(requests) {
            historyList.innerHTML = requests.map(req => `
                <div class="bg-gray-800 p-3 rounded border border-gray-700 flex justify-between items-center">
                    <div>
                        <div class="font-bold text-gray-300">${req.student.nome}</div>
                        <div class="text-xs text-gray-500">Saída: ${new Date(req.confirmed_at).toLocaleTimeString('pt-BR', {hour: '2-digit', minute:'2-digit'})}</div>
                    </div>
                    <div class="text-green-500 text-xs font-bold uppercase">Liberado</div>
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
