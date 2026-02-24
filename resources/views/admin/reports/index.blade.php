<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Admin</title>
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
                        <span class="font-black text-lg md:text-xl leading-none tracking-tighter uppercase text-green-800">Relatórios e Auditoria</span>
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

    <main class="container mx-auto p-6 max-w-4xl">
        <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight mb-8">Central de Relatórios</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('admin.reports.releases') }}" class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 hover:shadow-md hover:-translate-y-1 transition group">
                <div class="bg-green-100 text-green-600 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Relatório de Saídas</h3>
                <p class="text-sm text-gray-500">Visualize todas as solicitações de saída filtrando por data e status.</p>
            </a>

            <a href="{{ route('admin.reports.student') }}" class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 hover:shadow-md hover:-translate-y-1 transition group">
                <div class="bg-emerald-100 text-emerald-600 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Relatório por Aluno</h3>
                <p class="text-sm text-gray-500">Busque o histórico completo de solicitações de um aluno específico via matrícula.</p>
            </a>

            <a href="{{ route('admin.reports.chaves') }}" class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200 hover:shadow-md hover:-translate-y-1 transition group">
                <div class="bg-blue-100 text-blue-600 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Relatório de Chaves</h3>
                <p class="text-sm text-gray-500">Monitore a movimentação de chaves e autorizações de acesso aos laboratórios.</p>
            </a>
        </div>
    </main>
</body>
</html>
