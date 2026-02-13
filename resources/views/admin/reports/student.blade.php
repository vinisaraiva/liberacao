<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório por Aluno - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
    <nav class="bg-green-700 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Relatório por Aluno</h1>
            <a href="{{ route('admin.reports.index') }}" class="text-green-200 hover:text-white font-medium">Voltar</a>
        </div>
    </nav>

    <main class="container mx-auto p-6 max-w-5xl">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 mb-8">
            <form action="{{ route('admin.reports.student') }}" method="GET" class="flex gap-4 items-end">
                <div class="flex-grow">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Matrícula do Aluno</label>
                    <input type="text" name="matricula" value="{{ request('matricula') }}" required class="w-full border rounded-lg py-3 px-4 text-lg focus:ring-2 focus:ring-green-500 font-mono" placeholder="Digite a matrícula...">
                </div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg transition shadow-md">
                    BUSCAR HISTÓRICO
                </button>
            </form>
        </div>

        @if($student)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-green-200 mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black text-gray-800 uppercase">{{ $student->nome }}</h2>
                    <p class="text-sm text-gray-500 font-mono">Matrícula: {{ $student->matricula }}</p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Total de Solicitações</span>
                    <span class="text-3xl font-black text-green-600">{{ $releases->total() }}</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Data/Hora</th>
                            <th class="px-6 py-4">Motivo</th>
                            <th class="px-6 py-4">Responsável</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($releases as $release)
                            <tr class="hover:bg-gray-50 transition text-sm">
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $release->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-medium text-gray-700">{{ $release->motivo }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500">
                                    {{ $release->created_by_name_snapshot }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $release->status == 'released' ? 'bg-emerald-100 text-emerald-700' : ($release->status == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ $release->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">Nenhum registro de saída para este aluno.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4 bg-gray-50 border-t">
                    {{ $releases->links() }}
                </div>
            </div>
        @elseif(request('matricula'))
            <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r-2xl text-red-700">
                <p class="font-bold">Aluno não encontrado.</p>
                <p class="text-sm">Verifique se a matrícula está correta.</p>
            </div>
        @endif
    </main>
</body>
</html>
