<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
    <nav class="bg-green-700 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Admin - Relatórios</h1>
            <a href="/dashboard" class="text-green-200 hover:text-white font-medium">Dashboard</a>
        </div>
    </nav>

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
        </div>
    </main>
</body>
</html>
