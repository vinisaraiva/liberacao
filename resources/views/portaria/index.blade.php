<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portaria - Hub de Acesso</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen flex flex-col font-sans">
    <header class="bg-green-700 pt-8 pb-4 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-green-600 rounded-full blur-3xl opacity-20 -mr-32 -mt-32"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="bg-white rounded-2xl shadow-xl flex items-center justify-between p-2 md:p-4 border border-white/20">
                <div class="flex items-center gap-4 group">
                    <div class="bg-gray-50 p-2 rounded-xl">
                        <img src="{{ asset('images/logo-ifba.png') }}" alt="IFBA Logo" class="h-10 md:h-12 w-auto">
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="font-black text-lg md:text-xl leading-none tracking-tighter uppercase text-green-800">Terminal Portaria</span>
                        <span class="text-[9px] md:text-[10px] text-green-600 font-bold uppercase tracking-[0.2em] mt-1">{{ \App\Models\Setting::get('campus_name', 'Campus Vitória da Conquista') }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="bg-green-50 text-green-700 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm border border-green-100">
                        Acesso Restrito
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center p-6">
        <div class="max-w-4xl w-full text-center">
        <h1 class="text-5xl md:text-7xl font-black mb-6 uppercase tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-green-400 leading-none">
            Painel Portaria
        </h1>
        <p class="text-gray-400 mb-12 text-xl md:text-2xl font-medium">Selecione o tipo de liberação para acessar o terminal.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Alunos -->
            <a href="{{ route('portaria.alunos') }}" class="group relative bg-gray-800 rounded-3xl p-8 border border-gray-700 hover:border-green-500 transition-all shadow-2xl overflow-hidden hover:-translate-y-2">
                <div class="absolute inset-0 bg-green-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-20 h-20 bg-green-900/30 rounded-2xl flex items-center justify-center mb-6 text-green-500 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <h2 class="text-3xl font-black uppercase mb-3">Liberação Alunos</h2>
                    <p class="text-gray-400 text-lg">Controle de saída antecipada e autorizações acadêmicas.</p>
                </div>
            </a>

            <!-- Chaves -->
            <a href="{{ route('portaria.chaves') }}" class="group relative bg-gray-800 rounded-3xl p-8 border border-gray-700 hover:border-blue-500 transition-all shadow-2xl overflow-hidden hover:-translate-y-2">
                <div class="absolute inset-0 bg-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-20 h-20 bg-blue-900/30 rounded-2xl flex items-center justify-center mb-6 text-blue-500 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                    </div>
                    <h2 class="text-3xl font-black uppercase mb-3">Controle de Chaves</h2>
                    <p class="text-gray-400 text-lg">Retirada e devolução de chaves de laboratórios.</p>
                </div>
            </a>
        </div>

        <div class="mt-16">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-300 font-black uppercase text-sm tracking-widest border-b-2 border-gray-800 transition py-2">Voltar ao Dashboard</a>
        </div>
    </div>
</body>
</html>
