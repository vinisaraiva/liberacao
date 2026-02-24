<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Liberação</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .bg-mesh {
            background-color: #166534;
            background-image: 
                radial-gradient(at 0% 0%, hsla(142,72%,15%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(142,72%,10%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(142,72%,20%,1) 0, transparent 50%);
        }
    </style>
</head>
<body class="bg-mesh min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <div class="inline-flex transition-transform hover:scale-110 duration-500">
                <img src="{{ asset('images/logo-ifba.png') }}" alt="IFBA Logo" class="h-32 w-auto">
            </div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight">LiberAção</h1>
            <p class="text-green-200 mt-2 font-black uppercase text-[10px] tracking-[0.2em]">{{ \App\Models\Setting::get('campus_name', 'Campus Vitória da Conquista') }}</p>
        </div>

        <div class="glass p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500"></div>
            
            <h2 class="text-2xl font-black text-gray-800 mb-8">Acessar Painel</h2>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded-r-xl">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="username" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Usuário / Login</label>
                    <input type="text" name="username" id="username" required autofocus 
                           class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-4 px-5 text-gray-800 focus:outline-none focus:ring-4 focus:ring-green-500/10 focus:border-green-500 transition-all placeholder-gray-300"
                           placeholder="Digite seu usuário">
                </div>

                <div>
                    <label for="password" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Senha de Acesso</label>
                    <input type="password" name="password" id="password" required 
                           class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-4 px-5 text-gray-800 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder-gray-300"
                           placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between px-1">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-5 h-5 rounded-lg border-gray-300 text-green-600 focus:ring-green-500 transition cursor-pointer">
                        <span class="text-sm text-gray-500 group-hover:text-gray-700 transition">Lembrar-me</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-green-200 hover:shadow-green-300 transform active:scale-[0.98] transition-all text-center uppercase tracking-widest text-sm">
                    Entrar no Sistema
                </button>
            </form>

            <div class="mt-10 pt-8 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400">© 2026 Antigravity Systems. <br> Todos os direitos reservados.</p>
            </div>
        </div>
        
        <div class="mt-8 flex justify-center gap-6 text-green-200/50 text-sm font-medium">
            <span class="flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-green-400/50"></div> Segurança Ponta a Ponta
            </span>
            <span class="flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-green-400/50"></div> Monitoramento Real-time
            </span>
        </div>
    </div>
</body>
</html>
