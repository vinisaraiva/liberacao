<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Chaves - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
    <header class="bg-green-700 pt-8 pb-4 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-green-600 rounded-full blur-3xl opacity-20 -mr-32 -mt-32"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="bg-white rounded-2xl shadow-xl flex items-center justify-between p-2 md:p-4 border border-white/20">
                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-4 group">
                    <div class="bg-gray-50 p-2 rounded-xl group-hover:bg-white transition-colors duration-300">
                        <img src="{{ asset('images/logo-ifba.png') }}" alt="IFBA Logo" class="h-10 md:h-12 w-auto group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-black text-lg md:text-xl leading-none tracking-tighter uppercase text-green-800">Relatório de Chaves</span>
                        <span class="text-[9px] md:text-[10px] text-green-600 font-bold uppercase tracking-[0.2em] mt-1">{{ \App\Models\Setting::get('campus_name', 'Campus Vitória da Conquista') }}</span>
                    </div>
                </a>

                <div class="flex items-center gap-6">
                    <a href="{{ route('admin.reports.index') }}" class="bg-green-50 hover:bg-green-100 text-green-700 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-300 shadow-sm">
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto p-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 mb-6">
            <form action="{{ route('admin.reports.chaves') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Data Inicial</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Data Final</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Sala</label>
                    <select name="laboratorio_id" class="w-full border rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach($laboratorios as $lab)
                            <option value="{{ $lab->id }}" {{ request('laboratorio_id') == $lab->id ? 'selected' : '' }}>{{ $lab->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Status</label>
                    <select name="status" class="w-full border rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="ATIVA" {{ request('status') == 'ATIVA' ? 'selected' : '' }}>ATIVA</option>
                        <option value="CANCELADA" {{ request('status') == 'CANCELADA' ? 'selected' : '' }}>CANCELADA</option>
                        <option value="EXPIRADA" {{ request('status') == 'EXPIRADA' ? 'selected' : '' }}>EXPIRADA</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition shadow-md uppercase text-xs tracking-widest">
                    FILTRAR
                </button>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Data / Autorizador</th>
                            <th class="px-6 py-4">Aluno / Sala</th>
                            <th class="px-6 py-4">Status / Validade</th>
                            <th class="px-6 py-4">Movimentação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($autorizacoes as $auth)
                            <tr class="hover:bg-gray-50 transition text-sm">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">{{ $auth->created_at->format('d/m/Y H:i') }}</div>
                                    <div class="text-[10px] text-gray-400 uppercase">Por: {{ $auth->autorizado_por_nome_snapshot }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800 uppercase">{{ $auth->student->nome }}</div>
                                    <div class="text-blue-600 font-bold text-[10px] uppercase">{{ $auth->laboratorio->nome }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @php
                                            $statusColors = [
                                                'ATIVA' => 'bg-green-100 text-green-700',
                                                'CANCELADA' => 'bg-red-100 text-red-700',
                                                'EXPIRADA' => 'bg-gray-100 text-gray-500'
                                            ];
                                            $color = $statusColors[$auth->status] ?? 'bg-blue-100 text-blue-700';
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $color }}">
                                            {{ $auth->status }}
                                        </span>
                                    </div>
                                    <div class="text-[10px] text-gray-400 mt-1">Válido até: {{ $auth->valid_until->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $retirada = $auth->movimentacoes->where('acao', 'RETIRADA')->first();
                                        $devolucao = $auth->movimentacoes->where('acao', 'DEVOLUCAO')->first();
                                    @endphp
                                    <div class="flex flex-col gap-1 text-[10px]">
                                        @if($retirada)
                                            <div class="flex items-center gap-1">
                                                <span class="text-blue-600 font-black uppercase">Retirada:</span>
                                                <span class="text-gray-600">{{ $retirada->realizado_em->format('H:i') }}</span>
                                            </div>
                                        @endif
                                        @if($devolucao)
                                            <div class="flex items-center gap-1">
                                                <span class="text-emerald-600 font-black uppercase">Devolvida:</span>
                                                <span class="text-gray-600">{{ $devolucao->realizado_em->format('H:i') }}</span>
                                            </div>
                                        @endif
                                        @if(!$retirada && !$devolucao)
                                            <span class="text-gray-300 italic">Sem movimentação</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">Nenhum registro encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 bg-gray-50 border-t">
                {{ $autorizacoes->links() }}
            </div>
        </div>
    </main>
</body>
</html>
