<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistema de Liberação - IFBA</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100 min-h-screen flex flex-col font-sans antialiased">
        <header class="bg-green-700 pt-12 pb-24 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-green-600 rounded-full blur-3xl opacity-20 -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-green-800 rounded-full blur-2xl opacity-30 -ml-32 -mb-32"></div>
            
            <div class="container mx-auto px-6 relative z-10 text-center text-white">
                <div class="inline-block bg-white p-4 rounded-3xl shadow-2xl mb-8 transform hover:scale-105 transition-transform duration-500">
                    <img src="{{ asset('images/logo-ifba.png') }}" alt="IFBA Logo" class="h-24 md:h-32 w-auto">
                </div>
                <h1 class="text-4xl md:text-6xl font-black uppercase tracking-tighter leading-none mb-4">
                    Sistema de Liberação
                </h1>
                <p class="text-green-100 text-lg md:text-xl font-medium tracking-wide uppercase opacity-90">
                    {{ \App\Models\Setting::get('campus_name', 'Campus Vitória da Conquista') }}
                </p>
            </div>
        </header>

        <main class="container mx-auto px-6 -mt-16 relative z-20 flex-grow">
            <div class="max-w-4xl mx-auto flex flex-col gap-8">
                <div class="bg-white rounded-[32px] shadow-2xl p-8 md:p-12 border border-white/20 text-center">
                    <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight mb-8">Acesso ao Sistema</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="group relative bg-green-600 hover:bg-green-700 text-white p-8 rounded-3xl transition-all shadow-xl hover:-translate-y-1 overflow-hidden">
                                <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <span class="relative z-10 block text-2xl font-black uppercase tracking-widest">Painel de Controle</span>
                                <span class="relative z-10 block text-green-100 text-sm mt-2">Acessar meu Dashboard</span>
                            </a>
                            
                            <a href="{{ route('portaria') }}" class="group relative bg-gray-900 hover:bg-black text-white p-8 rounded-3xl transition-all shadow-xl hover:-translate-y-1 overflow-hidden">
                                <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <span class="relative z-10 block text-2xl font-black uppercase tracking-widest">Terminal Portaria</span>
                                <span class="relative z-10 block text-gray-400 text-sm mt-2">Acesso para Porteiros e Vigilantes</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="group md:col-span-2 relative bg-green-600 hover:bg-green-700 text-white p-10 rounded-3xl transition-all shadow-xl hover:-translate-y-1 overflow-hidden">
                                <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <span class="relative z-10 block text-3xl font-black uppercase tracking-widest">Entrar no Sistema</span>
                                <span class="relative z-10 block text-green-100 mt-2 opacity-80">Clique aqui para realizar o login administrativo</span>
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    <div class="bg-white/60 backdrop-blur-sm p-6 rounded-3xl border border-white/40 text-center group hover:bg-white transition-colors">
                        <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-green-600 group-hover:scale-110 transition-transform">
                            📊
                        </div>
                        <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest mb-1">Auditoria</h3>
                        <p class="text-gray-500 text-[10px] leading-relaxed uppercase font-bold">Relatórios detalhados em tempo real</p>
                    </div>
                    
                    <div class="bg-white/60 backdrop-blur-sm p-6 rounded-3xl border border-white/40 text-center group hover:bg-white transition-colors">
                        <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-600 group-hover:scale-110 transition-transform">
                            🔑
                        </div>
                        <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest mb-1">Chaves</h3>
                        <p class="text-gray-500 text-[10px] leading-relaxed uppercase font-bold">Controle rigoroso de laboratórios</p>
                    </div>

                    <div class="bg-white/60 backdrop-blur-sm p-6 rounded-3xl border border-white/40 text-center group hover:bg-white transition-colors">
                        <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-amber-600 group-hover:scale-110 transition-transform">
                            🛡️
                        </div>
                        <h3 class="font-black text-gray-800 uppercase text-xs tracking-widest mb-1">Segurança</h3>
                        <p class="text-gray-500 text-[10px] leading-relaxed uppercase font-bold">Processos validados e auditáveis</p>
                    </div>
                </div>
            </div>
        </main>

        <footer class="container mx-auto px-6 py-8 text-center text-gray-400 text-[10px] font-black uppercase tracking-[0.3em]">
            &copy; {{ date('Y') }} IFBA - Sistema de Liberação Automatizada
        </footer>
    </body>
</html>
