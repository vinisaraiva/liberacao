<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Liberação - Sistema de Liberação</title>
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
                    <div class="flex flex-col">
                        <span class="font-black text-lg md:text-xl leading-none tracking-tighter uppercase text-green-800">Emitir Liberação</span>
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
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="p-6 border-b bg-gray-50">
                <h2 class="text-xl font-bold text-gray-800">Nova Solicitação de Saída</h2>
                <p class="text-sm text-gray-500">Busque o aluno pela matrícula ou preencha os dados caso seja o primeiro acesso.</p>
            </div>

            <div class="p-6">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <form id="releaseForm" action="/api/release-requests" method="POST">
                    @csrf
                    
                    <!-- Search / Matricula Section -->
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Matrícula do Aluno</label>
                        <div class="flex gap-2">
                            <input type="text" name="matricula" id="matricula" required 
                                class="flex-grow shadow border rounded py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500 text-lg font-mono" 
                                placeholder="Digite a matrícula e pressione Enter">
                            <button type="button" onclick="searchStudent()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded transition">
                                Buscar
                            </button>
                        </div>
                    </div>

                    <!-- Student Details (Always visible but can be populated) -->
                    <div id="studentSection" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 p-6 bg-green-50 rounded-lg border border-green-100">
                        <div class="md:col-span-2">
                            <h3 class="font-bold text-green-900 border-b border-green-200 pb-2 mb-4">Dados do Aluno</h3>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-green-700 uppercase mb-1">Nome Completo</label>
                            <input type="text" name="nome" id="nome" required 
                                class="w-full shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500 capitalize" 
                                placeholder="NOME DO ALUNO">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-green-700 uppercase mb-1">Curso</label>
                            <select name="curso_id" id="curso_id" required class="w-full shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">Selecione o Curso</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-green-700 uppercase mb-1">Série</label>
                            <select name="serie_id" id="serie_id" required class="w-full shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">Selecione a Série</option>
                                @foreach($series as $serie)
                                    <option value="{{ $serie->id }}">{{ $serie->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-green-700 uppercase mb-1">Turma</label>
                            <select name="turma_id" id="turma_id" required class="w-full shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">Selecione a Turma</option>
                                @foreach($turmas as $turma)
                                    <option value="{{ $turma->id }}" data-curso="{{ $turma->curso_id }}" data-serie="{{ $turma->serie_id }}">{{ $turma->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Release Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="md:col-span-2">
                            <h3 class="font-bold text-gray-800 border-b pb-2 mb-4">Informações da Liberação</h3>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Motivo</label>
                            <select name="motivo" id="motivo" required class="w-full shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="Saída Antecipada">Saída Antecipada</option>
                                <option value="Consulta Médica">Consulta Médica</option>
                                <option value="Assuntos Particulares">Assuntos Particulares</option>
                                <option value="Outros">Outros</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Setor Responsável</label>
                            <input type="text" name="sector" id="sector" required class="w-full shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ex: Coordenação Pedagógica">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Observação (Opcional)</label>
                            <textarea name="observacao" id="observacao" rows="2" class="w-full shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Detalhes adicionais..."></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end border-t pt-6 bg-gray-50 -mx-6 px-6">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-10 rounded-lg shadow-lg transform transition active:scale-95">
                            EMITIR LIBERAÇÃO
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        async function searchStudent() {
            const matricula = document.getElementById('matricula').value;
            if (!matricula) return;

            try {
                // We'll use the search API, but it might search by name. 
                // Let's assume we can fetch by matricula if we add an endpoint or use the search one carefully.
                // For now, let's use the search endpoint and filter client-side or assume it returns the student if matricula matches exactly.
                const response = await fetch(`/api/students/search?q=${matricula}`);
                const students = await response.json();
                
                const student = students.find(s => s.matricula === matricula);
                
                if (student) {
                    document.getElementById('nome').value = student.nome;
                    document.getElementById('curso_id').value = student.curso_id;
                    document.getElementById('serie_id').value = student.serie_id;
                    document.getElementById('turma_id').value = student.turma_id;
                    
                    // Add a visual highlight
                    document.getElementById('studentSection').classList.add('bg-green-50', 'border-green-200');
                    setTimeout(() => {
                        document.getElementById('studentSection').classList.remove('bg-green-50', 'border-green-200');
                    }, 1000);
                } else {
                    alert('Matrícula não encontrada. Preencha os dados do aluno para cadastrá-lo.');
                    document.getElementById('nome').focus();
                }
            } catch (err) {
                console.error('Erro ao buscar aluno:', err);
            }
        }

        document.getElementById('matricula').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchStudent();
            }
        });

        document.getElementById('releaseForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    alert('Liberação emitida com sucesso!');
                    window.location.href = '/dashboard';
                } else {
                    alert('Erro: ' + (result.message || 'Ocorreu um erro ao emitir a liberação.'));
                }
            } catch (err) {
                alert('Erro de conexão ao servidor.');
            }
        });
    </script>
</body>
</html>
