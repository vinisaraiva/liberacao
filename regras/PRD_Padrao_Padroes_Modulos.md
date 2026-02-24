# PRD Padrão + Padrões de Engenharia (para todos os módulos)

**Produto:** Aplicação Intranet (Portaria/Liberações)  
**Stack atual:** PHP puro + MySQL 8 + UI moderna responsiva (Tailwind) + Portaria kiosk (sem login)  
**Objetivo deste documento:** criar um **modelo de PRD** e um **padrão técnico/visual** para que todos os módulos futuros sejam construídos de forma consistente, segura e fácil de manter.

---

## 1) Decisão técnica (imparcial)

### 1.1 Vale a pena ter um PRD padrão?
✅ **Sim.** Ajuda a:
- evitar retrabalho e divergências entre módulos;
- manter consistência visual e de segurança;
- acelerar desenvolvimento no Cursor (prompts replicáveis);
- melhorar manutenção e onboarding de novos devs.

### 1.2 Comentários em português nas funções: devo adotar?
✅ **Sim, com regra.** Comentários em PT-BR ajudam a manutenção do time local.  
⚠️ Mas é importante seguir boas práticas:
- comentar **o porquê** (regras, decisões, exceções), não o óbvio;
- manter comentários curtos e atualizados;
- preferir nomes de funções/variáveis claros (inglês ou português padronizado) + comentários PT.

**Decisão:** Comentários em português serão adotados (comente como um dev pleno, sem redundancias, exageros ou formalidades excessivas), focando em **regras de negócio e pontos críticos**.

---

## 2) Design System (padrão visual)

### 2.1 Fundamentos
- **Fonte:** padrão do navegador (ou Inter se já estiver no projeto)
- **Layout:** container central + grid responsivo
- **Estilo:** limpo, institucional, com foco em legibilidade e rapidez
- **Tailwind:** usado como base (CDN ou local, conforme rede)

### 2.2 Componentes padrão (obrigatórios)
1) **Layout base**
   - Topbar (nome do sistema + usuário logado)
   - Menu lateral (desktop) / menu colapsado (mobile)
   - Área de conteúdo com título + ações

2) **Botões**
   - Primário (ação principal)
   - Secundário (ações auxiliares)
   - Perigoso (cancelar/excluir)

3) **Inputs**
   - Campo texto, select, date/time
   - Busca com Enter (quando fizer sentido)
   - Mensagens de erro abaixo do campo

4) **Cards**
   - Card de aluno (resumo)
   - Card de autorização/registro
   - Card KPI (para relatórios)

5) **Status “chips”**
   - ATIVA, CONFIRMADA, CANCELADA, EXPIRADA, PENDENTE (exemplo)
   - Sempre consistente entre módulos

6) **Toasts/Alerts**
   - Sucesso, erro, aviso
   - Não usar pop-ups intrusivos

### 2.3 Tela Portaria (Kiosk)
- Fonte maior, botões grandes, poucas distrações
- “Hoje” como padrão
- 1–2 cliques para completar tarefa
- Feedback claro após registrar ação

---

## 3) Padrões de Engenharia (boas práticas)

### 3.1 Arquitetura (PHP puro, organizada)
**Princípio:** controllers finos + services com regras de negócio + models/repositories com consultas.

Estrutura sugerida:
```
/public
  index.php
/app
  /Config
  /Db
  /Controllers
  /Services
  /Models
  /Middleware
  /Views
/database
  schema.sql
  patches/
  seeds.sql (opcional)
```

### 3.2 Conexão e consultas ao banco
- **PDO** obrigatório
- **Prepared statements** obrigatório
- Sem SQL concatenado com input do usuário
- Índices e chaves estrangeiras definidos no schema
- Paginação em listas/histórico

### 3.3 Segurança mínima obrigatória
- Login com sessão para usuários internos (admin/autorizadores)
- `password_hash()` e `password_verify()`
- `session_regenerate_id(true)` no login
- CSRF token em formulários POST
- RBAC por **role** e, se adotado, por **módulo**
- Portaria sem login:
  - token do terminal + restrição por IP/sub-rede (quando possível)

### 3.4 Padrão de código (Clean Code)
- Funções pequenas e com responsabilidade única
- Nomes claros e consistentes
- Validar entradas na borda (Controllers)
- Regras de negócio no Service
- Evitar duplicação (DRY)
- Tratamento consistente de erros (mensagens amigáveis)

### 3.5 Comentários em português (regra padrão)
**Comentar:**
- regras de negócio (“uma autorização ativa por aluno”)
- exceções/edge cases
- decisões de segurança (token terminal, IP allowlist)
- queries complexas

**Não comentar:**
- o óbvio (“atribui variável X”)
- código autoexplicativo com bons nomes

---

## 4) Template de PRD (modelo para todos os módulos)

> Copie este template e preencha para cada módulo novo.

### 4.1 Cabeçalho
- **Nome do módulo:**
- **Versão:**
- **Responsáveis/Stakeholders:**
- **Objetivo do módulo (1 frase):**
- **Fora de escopo (MVP):**

### 4.2 Contexto e problema
- Qual processo atual?
- Quais dores (erros, riscos, auditoria, tempo)?
- Quem é impactado?

### 4.3 Objetivos e métricas
- Objetivos (3–5)
- Métricas de sucesso (tempo de atendimento, redução de erros, rastreabilidade)

### 4.4 Personas e permissões
- Admin (o que pode)
- Usuário interno (o que pode)
- Portaria (se houver)
- Regras de acesso (role/module)

### 4.5 Fluxos principais (jornadas)
- Fluxo A (passo a passo)
- Fluxo B
- Fluxo C (cancelamento/expiração, se aplicável)

### 4.6 Regras de negócio
- Lista numerada e objetiva
- Regras de validade, unicidade, bloqueios
- Auditoria obrigatória (o que logar)

### 4.7 Telas e UX
- Lista de telas
- Campos e ações
- Estados vazios/erro/sucesso
- Adesão ao Design System (chips, cards, etc.)

### 4.8 Modelo de dados (DB)
- Entidades novas
- Campos e tipos
- Índices
- Relacionamentos
- DDL (SQL)

### 4.9 Rotas/Endpoints internos
- Rotas web (GET/POST)
- Endpoints kiosk (se houver)
- Regras de middleware

### 4.10 Segurança e LGPD
- Quem vê o quê?
- Restrições intranet
- Retenção de dados (se necessário)

### 4.11 Critérios de aceite
- Itens testáveis (Given/When/Then ou lista objetiva)

### 4.12 Backlog (épicos e histórias)
- Épico 1: …
- Épico 2: …

### 4.13 Prompt do Cursor (auditar + implementar)
- Um prompt completo (padrão) para o Cursor:
  - auditar repositório
  - implementar módulo se não existir
  - listar arquivos e como testar

### 4.14 Checklist de teste manual
- Passo a passo para admin, usuário, portaria

---

## 5) Template de Prompt (Cursor) — padrão para qualquer módulo

> Substitua os trechos em MAIÚSCULO.

```text
Você é um Tech Lead sênior. Audite o repositório e verifique se já existe o módulo: NOME_DO_MODULO.
Se não existir ou estiver incompleto, implemente conforme o PRD do módulo.

Regras gerais:
- PHP puro, PDO, prepared statements.
- UI moderna com Tailwind seguindo o Design System do projeto.
- Controllers finos, regras no Service.
- Comentários em português nas funções principais (regras de negócio e segurança).
- CSRF em forms POST, sessões seguras, RBAC por role/módulo.
- Registrar audit_logs para ações críticas.

Entregáveis:
1) SQL (schema/patch) e/ou migrations equivalentes.
2) Controllers + Services + Models necessários.
3) Views (Blade não; usar templates PHP/HTML) com Tailwind.
4) Rotas no router.
5) Como testar manualmente (passo a passo).
Ao final, liste arquivos criados/alterados.
```

---

## 6) Recomendação final (decisão)

✅ **Vamos adotar este PRD padrão e estes padrões técnicos/visuais** para todos os módulos.  
✅ **Comentários em português** serão parte do padrão (com disciplina: comentar regras e decisões).  
✅ **Design System mínimo** com Tailwind para garantir consistência e rapidez.  
✅ **Boas práticas**: PDO + prepared statements + CSRF + sessões seguras + RBAC + auditoria.

---

**Fim do documento**
