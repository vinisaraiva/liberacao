<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Autorizar - Controle de Chaves</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { -webkit-tap-highlight-color: transparent; }
        .sticky-bottom { position: sticky; bottom: 0; background: white; border-top: 1px solid #e5e7eb; padding: 1rem; z-index: 40; }
        .app-card { border-radius: 1.5rem; }
        .input-group:focus-within { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen font-sans pb-24 md:pb-0">
    <header class="bg-green-700 pt-8 pb-4 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-green-600 rounded-full blur-3xl opacity-20 -mr-32 -mt-32"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="bg-white rounded-2xl shadow-xl flex items-center justify-between p-2 md:p-4 border border-white/20">
                <a href="/dashboard" class="flex items-center gap-4 group">
                    <div class="bg-gray-50 p-2 rounded-xl group-hover:bg-white transition-colors duration-300">
                        <img src="{{ asset('images/logo-ifba.png') }}" alt="IFBA Logo" class="h-10 md:h-12 w-auto group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="font-black text-lg md:text-xl leading-none tracking-tighter uppercase text-green-800">Autorizar Uso de Chave</span>
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
            <a href="/dashboard" class="p-2 bg-gray-50 rounded-xl text-gray-400 hover:text-blue-600 transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <span class="hidden md:block text-xs font-bold uppercase tracking-widest">Painel</span>
            </a>
        </div>
    </nav>

    <main class="container mx-auto p-4 max-w-lg">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 rounded-2xl border border-green-100 text-green-700 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 rounded-2xl border border-red-100 text-red-700 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                <span class="font-bold text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <form id="authChaveForm" action="{{ route('chaves.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Step 1: Student -->
            <div class="bg-white p-6 app-card shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-black uppercase tracking-tight leading-none text-gray-800">Identificar Aluno</h2>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Busca por Matrícula ou Nome</p>
                    </div>
                </div>

                <div class="space-y-4 relative">
                    <div class="input-group border border-gray-200 rounded-2xl flex items-center px-4 py-1 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input type="text" id="student_search" autocomplete="off"
                            class="w-full py-4 px-3 bg-transparent text-lg font-bold placeholder-gray-400 focus:outline-none" 
                            placeholder="Procurar...">
                    </div>
                    
                    <div id="resultsDropdown" class="hidden absolute z-50 w-full bg-white mt-1 rounded-3xl shadow-2xl border border-gray-100 max-h-64 overflow-y-auto p-2">
                        <!-- Dropdown Items -->
                    </div>

                    <div id="studentDetailsCard" class="hidden animate-in fade-in zoom-in duration-300">
                        <div class="bg-blue-50/50 p-4 rounded-3xl border border-blue-100/50 relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 text-blue-100 scale-150 rotate-12 opacity-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                            </div>
                            <div class="relative z-10">
                                <span class="bg-blue-600 text-white text-[8px] font-black px-2 py-0.5 rounded-full uppercase tracking-widest mb-2 inline-block">Aluno Selecionado</span>
                                <input type="text" name="nome" id="nome" readonly class="bg-transparent w-full text-xl font-black text-blue-900 border-none p-0 focus:ring-0 uppercase">
                                <div class="flex gap-2 mt-2">
                                    <span id="display_matricula" class="text-xs font-mono font-bold text-blue-600 bg-blue-100 px-2 py-0.5 rounded-lg border border-blue-200"></span>
                                    <span id="display_turma" class="text-xs font-bold text-blue-500 bg-white px-2 py-0.5 rounded-lg border border-blue-100 uppercase"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="matricula" id="matricula">
                    <input type="hidden" name="curso_id" id="curso_id">
                    <input type="hidden" name="serie_id" id="serie_id">
                    <input type="hidden" name="turma_id" id="turma_id">
                </div>
            </div>

            <!-- Step 2: Location & Time -->
            <div class="bg-white p-6 app-card shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-black uppercase tracking-tight leading-none text-gray-800">Local e Tempo</h2>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Defina onde e por quanto tempo</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Laboratório ou Sala</label>
                        <select name="laboratorio_id" id="laboratorio_id" required 
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-4 px-4 text-lg font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all appearance-none uppercase">
                            <option value="">Selecione o Local...</option>
                            @foreach($laboratorios as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Válido de</label>
                            <input type="datetime-local" name="valid_from" id="valid_from" value="{{ now()->format('Y-m-d\TH:i') }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-4 px-4 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Válido até</label>
                            <input type="datetime-local" name="valid_until" id="valid_until" required 
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-4 px-4 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                        </div>
                    </div>
                        
                        <div class="grid grid-cols-4 gap-2 pt-2">
                            <button type="button" onclick="setRelativeTime(60)" class="bg-white border border-gray-200 hover:border-blue-500 hover:text-blue-600 text-[10px] font-black py-3 rounded-xl transition uppercase tracking-tighter transition-all active:scale-90 shadow-sm">+1h</button>
                            <button type="button" onclick="setRelativeTime(1440)" class="bg-white border border-gray-200 hover:border-blue-500 hover:text-blue-600 text-[10px] font-black py-3 rounded-xl transition uppercase tracking-tighter transition-all active:scale-90 shadow-sm">1 Dia</button>
                            <button type="button" onclick="setRelativeTime(10080)" class="bg-white border border-gray-200 hover:border-blue-500 hover:text-blue-600 text-[10px] font-black py-3 rounded-xl transition uppercase tracking-tighter transition-all active:scale-90 shadow-sm">1 Sem</button>
                            <button type="button" onclick="setRelativeTime(43200)" class="bg-white border border-gray-200 hover:border-blue-500 hover:text-blue-600 text-[10px] font-black py-3 rounded-xl transition uppercase tracking-tighter transition-all active:scale-90 shadow-sm">1 Mês</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Optional Card -->
            <div class="bg-white p-6 app-card shadow-sm border border-gray-100">
                 <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <h3 class="text-xs font-black uppercase text-gray-500 tracking-widest">Observações Extra</h3>
                </div>
                <textarea name="observacao" id="observacao" rows="2" 
                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-4 px-4 text-gray-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 transition-all" 
                    placeholder="Algo a adicionar? (opcional)"></textarea>
            </div>

            <!-- Desktop Footer -->
            <div class="hidden md:flex justify-end pt-4">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-5 px-8 rounded-3xl shadow-xl transition-all transform active:scale-95 uppercase tracking-widest flex items-center justify-center gap-2">
                    <span>Autorizar Retirada</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </button>
            </div>
        </form>
    </main>

    <!-- Mobile Sticky Footer -->
    <div class="sticky-bottom md:hidden">
        <button type="button" onclick="document.getElementById('authChaveForm').dispatchEvent(new Event('submit'))" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-5 px-8 rounded-2xl shadow-xl transition-all transform active:scale-95 uppercase tracking-widest flex items-center justify-center gap-2">
            <span>Autorizar Retirada</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        </button>
    </div>

    <script>
        let searchTimeout;
        const searchInput = document.getElementById('student_search');
        const dropdown = document.getElementById('resultsDropdown');
        const studentCard = document.getElementById('studentDetailsCard');

        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(searchStudent, 400);
        });

        async function searchStudent() {
            const term = searchInput.value;
            if (term.length < 2) {
                dropdown.classList.add('hidden');
                return;
            }

            try {
                const response = await fetch(`/api/students/search?q=${term}`);
                const students = await response.json();
                
                dropdown.innerHTML = '';
                
                if (students.length === 0) {
                    dropdown.innerHTML = '<div class="p-4 text-center text-gray-400 text-sm italic uppercase font-bold tracking-tighter">Nenhum aluno encontrado</div>';
                    dropdown.classList.remove('hidden');
                    return;
                }

                students.forEach(student => {
                    const div = document.createElement('div');
                    div.className = 'p-4 hover:bg-blue-50 cursor-pointer rounded-2xl flex justify-between items-center transition-colors mb-1';
                    div.innerHTML = `
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500 font-black text-xs">
                                ${student.nome.charAt(0)}
                            </div>
                            <div>
                                <p class="font-black text-gray-800 uppercase text-xs tracking-tight">${student.nome}</p>
                                <p class="text-[10px] text-gray-400 font-mono tracking-tighter uppercase">ID: ${student.matricula}</p>
                            </div>
                        </div>
                        <span class="text-[8px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full font-black uppercase tracking-widest">${student.curso?.nome || 'ALUNO'}</span>
                    `;
                    div.onclick = () => selectStudent(student);
                    dropdown.appendChild(div);
                });
                dropdown.classList.remove('hidden');
            } catch (err) {
                console.error('Erro ao buscar:', err);
            }
        }

        function selectStudent(student) {
            document.getElementById('matricula').value = student.matricula;
            document.getElementById('nome').value = student.nome;
            document.getElementById('curso_id').value = student.curso_id;
            document.getElementById('serie_id').value = student.serie_id;
            document.getElementById('turma_id').value = student.turma_id;

            document.getElementById('display_matricula').innerText = student.matricula;
            document.getElementById('display_turma').innerText = student.turma?.nome || 'TURMA N/D';
            
            searchInput.value = '';
            dropdown.classList.add('hidden');
            studentCard.classList.remove('hidden');
            
            // Effect
            studentCard.classList.add('ring-4', 'ring-blue-500/10');
            setTimeout(() => studentCard.classList.remove('ring-4', 'ring-blue-500/10'), 600);
        }

        function setRelativeTime(minutes) {
            const now = new Date();
            now.setMinutes(now.getMinutes() + minutes);
            
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const mins = String(now.getMinutes()).padStart(2, '0');
            
            document.getElementById('valid_until').value = `${year}-${month}-${day}T${hours}:${mins}`;
        }

        // Initialize with default +4h
        window.addEventListener('load', () => setRelativeTime(240));

        // Form Submit
        document.getElementById('authChaveForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            if (!data.matricula) {
                alert('Por favor, selecione um aluno primeiro.');
                searchInput.focus();
                return;
            }

            if (data.valid_until) {
                data.valid_until = data.valid_until.replace('T', ' ') + ':00';
            }
            
            try {
                const submitBtn = document.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Processando...</span>';

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
                    alert('✓ Autorização concluída!');
                    window.location.href = '{{ route("chaves.history") }}';
                } else {
                    alert('Erro: ' + (result.message || 'Falha ao autorizar.'));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            } catch (err) {
                alert('Erro de conexão ao servidor.');
            }
        });

        // Click outside dropdown
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target)) dropdown.classList.add('hidden');
        });
    </script>
</body>
</html>
