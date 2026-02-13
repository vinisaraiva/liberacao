<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Liberações - Sistema de Liberação</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <nav class="bg-green-700 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Histórico Geral</h1>
            <a href="/dashboard" class="text-green-200 hover:text-white">Voltar ao Dashboard</a>
        </div>
    </nav>

    <main class="container mx-auto p-6 max-w-6xl">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="p-6 border-b bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Registros de Saída</h2>
                    <p class="text-sm text-gray-500">Histórico completo de solicitações e seus status finais.</p>
                </div>
                
                <!-- Filters -->
                <div class="flex gap-2">
                    <select id="filterStatus" onchange="fetchHistory()" class="border rounded px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-green-500 font-medium">
                        <option value="">Todos os Status</option>
                        <option value="waiting">Pendente</option>
                        <option value="released">Confirmado</option>
                        <option value="cancelled">Cancelado</option>
                        <option value="expired">Expirado</option>
                    </select>
                    <button onclick="fetchHistory()" class="bg-green-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-green-700 transition">Filtrar</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-xs uppercase font-bold">
                            <th class="px-6 py-4">Data / Hora</th>
                            <th class="px-6 py-4">Aluno</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Responsável</th>
                            <th class="px-6 py-4">Portaria</th>
                        </tr>
                    </thead>
                    <tbody id="history-body" class="divide-y divide-gray-200">
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">Carregando dados...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 bg-gray-50 border-t flex justify-between items-center" id="pagination-controls">
                <!-- Pagination will be injected here -->
            </div>
        </div>
    </main>

    <script>
        let currentPage = 1;

        async function fetchHistory(page = 1) {
            currentPage = page;
            const status = document.getElementById('filterStatus').value;
            const url = `/api/release-requests?page=${page}&status=${status}&limit=15`;
            
            try {
                const response = await fetch(url);
                const data = await response.json();
                renderTable(data.data || []);
                renderPagination(data);
            } catch (err) {
                console.error('Erro ao buscar histórico:', err);
            }
        }

        function renderTable(items) {
            const body = document.getElementById('history-body');
            if (items.length === 0) {
                body.innerHTML = '<tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">Nenhum registro encontrado.</td></tr>';
                return;
            }

            body.innerHTML = items.map(item => {
                const statusColors = {
                    'waiting': 'bg-yellow-100 text-yellow-700',
                    'released': 'bg-green-100 text-green-700',
                    'cancelled': 'bg-red-100 text-red-700',
                    'expired': 'bg-purple-100 text-purple-700'
                };
                
                const statusLabels = {
                    'waiting': 'Pendente',
                    'released': 'Confirmado',
                    'cancelled': 'Cancelado',
                    'expired': 'Expirado'
                };

                return `
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-gray-700">
                            ${new Date(item.created_at).toLocaleDateString('pt-BR')} <br>
                            <span class="text-xs text-gray-400">${new Date(item.created_at).toLocaleTimeString('pt-BR', {hour:'2-digit', minute:'2-digit'})}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">${item.student ? item.student.nome : 'N/A'}</div>
                            <div class="text-xs text-gray-500">${item.student ? item.student.matricula : 'N/A'}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase ${statusColors[item.status] || 'bg-gray-100'}">
                                ${statusLabels[item.status] || item.status}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            ${item.created_by_name_snapshot || 'Sistema'}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            ${item.confirmed_terminal_name_snapshot || '-'}
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function renderPagination(data) {
            const controls = document.getElementById('pagination-controls');
            if (!data.last_page || data.last_page <= 1) {
                controls.innerHTML = '';
                return;
            }

            let html = `<div class="text-sm text-gray-500">Página ${data.current_page} de ${data.last_page}</div>`;
            html += '<div class="flex gap-2">';
            
            if (data.prev_page_url) {
                html += `<button onclick="fetchHistory(${data.current_page - 1})" class="px-3 py-1 bg-white border rounded hover:bg-gray-100 text-sm">Anterior</button>`;
            }
            
            if (data.next_page_url) {
                html += `<button onclick="fetchHistory(${data.current_page + 1})" class="px-3 py-1 bg-white border rounded hover:bg-gray-100 text-sm">Próxima</button>`;
            }
            
            html += '</div>';
            controls.innerHTML = html;
        }

        // Initial load
        fetchHistory();
    </script>
</body>
</html>
