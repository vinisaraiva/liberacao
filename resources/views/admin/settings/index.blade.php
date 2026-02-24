<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - Admin</title>
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
                        <span class="font-black text-lg md:text-xl leading-none tracking-tighter uppercase text-green-800">Admin - Configurações</span>
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

    <main class="container mx-auto p-6 max-w-2xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight">Ajustes do Sistema</h2>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nome do Campus</label>
                    <input type="text" name="campus_name" value="{{ $campusName }}" required 
                        class="w-full border rounded-xl py-3 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 text-lg font-bold" 
                        placeholder="Ex: Campus Vitória da Conquista">
                    <p class="text-[10px] text-gray-400 mt-2 italic px-1">Este nome será exibido no topo do dashboard e em outros locais do sistema.</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl transition shadow-md uppercase text-sm tracking-widest">
                        SALVAR ALTERAÇÕES
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
