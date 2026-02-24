<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Chaves - Sistema de Liberação</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <header class="bg-green-700 pt-8 pb-4 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-green-600 rounded-full blur-3xl opacity-20 -mr-32 -mt-32"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="bg-white rounded-2xl shadow-xl flex items-center justify-between p-2 md:p-4 border border-white/20">
                <a href="/dashboard" class="flex items-center gap-4 group">
                    <div class="bg-gray-50 p-2 rounded-xl group-hover:bg-white transition-colors duration-300">
                        <img src="{{ asset('images/logo-ifba.png') }}" alt="IFBA Logo" class="h-10 md:h-12 w-auto group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="font-black text-lg md:text-xl leading-none tracking-tighter uppercase text-green-800">Histórico de Chaves</span>
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

    <main class="container mx-auto p-6 max-w-6xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight">Movimentações de Chaves</h2>
            <a href="{{ route('chaves.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition transform active:scale-95 uppercase text-xs tracking-widest">
                Nova Autorização
            </a>
        </div>

        <!-- Filtros -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6 flex flex-wrap gap-4 items-end">
            <form action="{{ route('chaves.history') }}" method="GET" class="flex flex-wrap gap-4 items-end w-full">
                <div class="flex-grow min-w-[200px]">
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-wider">Laboratório</label>
                    <select name="laboratorio_id" class="w-full border rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">TODOS OS LOCAIS</option>
                        @foreach($laboratorios as $lab)
                            <option value="{{ $lab->id }}" {{ request('laboratorio_id') == $lab->id ? 'selected' : '' }}>{{ $lab->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-grow min-w-[150px]">
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-wider">Status</label>
                    <select name="status" class="w-full border rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none uppercase font-bold text-xs">
                        <option value="">TODOS OS STATUS</option>
                        <option value="ATIVA" {{ request('status') == 'ATIVA' ? 'selected' : '' }}>ATIVA</option>
                        <option value="CANCELADA" {{ request('status') == 'CANCELADA' ? 'selected' : '' }}>CANCELADA</option>
                        <option value="EXPIRADA" {{ request('status') == 'EXPIRADA' ? 'selected' : '' }}>EXPIRADA</option>
                    </select>
                </div>
                <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-black py-2.5 px-6 rounded-lg text-xs uppercase transition tracking-widest border border-gray-200">
                    Filtrar
                </button>
                <a href="{{ route('chaves.history') }}" class="text-gray-400 hover:text-gray-600 text-xs font-bold py-2.5">Limpar</a>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Data / Autorizador</th>
                            <th class="px-6 py-4">Aluno / Local</th>
                            <th class="px-6 py-4">Status / Validade</th>
                            <th class="px-6 py-4">Movimentação</th>
                            <th class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($autorizacoes as $auth)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-gray-800">{{ $auth->created_at->format('d/m/Y H:i') }}</div>
                                    <div class="text-[9px] text-gray-400 uppercase mt-0.5">Por: {{ $auth->autorizado_por_nome_snapshot }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-black text-gray-900 uppercase tracking-tighter">{{ $auth->student->nome }}</div>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                                        <span class="text-[10px] font-black text-blue-600 uppercase">{{ $auth->laboratorio->nome }}</span>
                                    </div>
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
                                        <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-tighter {{ $color }}">
                                            {{ $auth->status }}
                                        </span>
                                        <span class="text-[10px] text-gray-400 font-mono">até {{ $auth->valid_until->format('H:i') }}</span>
                                    </div>
                                    @if($auth->observacao)
                                        <div class="text-[9px] text-gray-500 italic mt-1 truncate max-w-[200px]" title="{{ $auth->observacao }}">"{{ $auth->observacao }}"</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $retirada = $auth->movimentacoes->where('acao', 'RETIRADA')->first();
                                        $devolucao = $auth->movimentacoes->where('acao', 'DEVOLUCAO')->first();
                                    @endphp
                                    <div class="flex flex-col gap-1">
                                        @if($retirada)
                                            <div class="flex items-center gap-2 text-[10px]">
                                                <span class="text-blue-600 font-black uppercase tracking-tighter">Ret:</span>
                                                <span class="text-gray-600 font-bold">{{ $retirada->realizado_em->format('H:i') }}</span>
                                                <span class="text-[8px] text-gray-400">[{{ $retirada->terminal_name_snapshot }}]</span>
                                            </div>
                                        @endif
                                        @if($devolucao)
                                            <div class="flex items-center gap-2 text-[10px]">
                                                <span class="text-emerald-500 font-black uppercase tracking-tighter">Dev:</span>
                                                <span class="text-gray-600 font-bold">{{ $devolucao->realizado_em->format('H:i') }}</span>
                                                <span class="text-[8px] text-gray-400">[{{ $devolucao->terminal_name_snapshot }}]</span>
                                            </div>
                                        @elseif($retirada)
                                            <div class="flex items-center gap-1.5">
                                                <span class="flex h-2 w-2 relative">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                                </span>
                                                <span class="text-[9px] font-black text-red-500 uppercase tracking-widest animate-pulse">Pendente</span>
                                            </div>
                                        @else
                                            <span class="text-[10px] text-gray-300 italic">Nenhuma ação ainda</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($auth->status === 'ATIVA')
                                        <form action="{{ route('chaves.cancel', $auth->id) }}" method="POST" onsubmit="return confirm('Deseja cancelar esta autorização?')">
                                            @csrf
                                            <button type="submit" class="text-red-400 hover:text-red-600 text-[10px] font-black uppercase tracking-tighter border-b border-transparent hover:border-red-600 transition-all">Cancelar</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center text-gray-400 flex flex-col items-center gap-2">
                                    <svg class="h-8 w-8 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span class="italic">Nenhum registro encontrado com os filtros aplicados.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($autorizacoes->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t">
                    {{ $autorizacoes->links() }}
                </div>
            @endif
        </div>
    </main>
</body>
</html>
