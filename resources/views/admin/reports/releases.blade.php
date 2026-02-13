<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Saídas - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
    <nav class="bg-green-700 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Relatório de Saídas</h1>
            <a href="{{ route('admin.reports.index') }}" class="text-green-200 hover:text-white font-medium">Voltar</a>
        </div>
    </nav>

    <main class="container mx-auto p-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 mb-6">
            <form action="{{ route('admin.reports.releases') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Data Inicial</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Data Final</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Status</label>
                    <select name="status" class="w-full border rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-green-500">
                        <option value="">Todos</option>
                        <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>Pendente</option>
                        <option value="released" {{ request('status') == 'released' ? 'selected' : '' }}>Liberado</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition shadow-md">
                    FILTRAR
                </button>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Data/Hora</th>
                        <th class="px-6 py-4">Aluno</th>
                        <th class="px-6 py-4">Motivo</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Portaria</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($releases as $release)
                        <tr class="hover:bg-gray-50 transition text-sm">
                            <td class="px-6 py-4 text-gray-600">
                                {{ $release->created_at->format('d/m/Y') }}<br>
                                <span class="text-xs text-gray-400">{{ $release->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800 uppercase">{{ $release->student->nome }}</div>
                                <div class="text-[10px] font-mono text-gray-500">{{ $release->student->matricula }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium text-gray-700">{{ $release->motivo }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $release->status == 'released' ? 'bg-emerald-100 text-emerald-700' : ($release->status == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ $release->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $release->confirmedTerminal->name ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Nenhum registro encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 bg-gray-50 border-t">
                {{ $releases->links() }}
            </div>
        </div>
    </main>
</body>
</html>
