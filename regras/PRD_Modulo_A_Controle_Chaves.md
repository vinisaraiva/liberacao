# PRD — Módulo A: Controle de Chaves de Laboratórios (Intranet)

**Produto:** Sistema interno (intranet) de Liberação/Portaria  
**Módulo:** A — Controle de Chaves de Laboratórios  
**Stack atual:** PHP puro + MySQL 8 + UI moderna (Tailwind) + Portaria em modo kiosk (sem login)  
**Versão:** MVP 1.0 (com devolução)

---

## 1) Contexto e problema

Hoje, a liberação/retirada de chaves de laboratórios na portaria pode ocorrer sem rastreio completo, gerando:
- entrega indevida (sem autorização)
- dificuldade de auditoria (quem autorizou? quem entregou? quando?)
- risco de perda de chave (retirada sem devolução)
- falta de histórico por laboratório/aluno/professor

Este módulo adiciona um fluxo controlado para **autorizar**, **entregar** e **registrar** a movimentação (retirada/devolução) de chaves.

---

## 2) Objetivo do módulo

Garantir que:
1. Somente alunos **autorizados** possam retirar chaves de laboratórios definidos.
2. A portaria registre **data/hora** da retirada e da devolução.
3. Exista histórico completo para auditoria por:
   - aluno
   - laboratório
   - autorizador
   - portaria/terminal
   - período

---

## 3) Perfis e acesso

### 3.1 Autorizador (com login)
Exemplos: professor, técnico de laboratório, coordenação, servidor autorizado.

Permissões:
- Buscar aluno (por matrícula/nome)
- Cadastrar aluno “por demanda” (se não existir)
- Criar autorização para retirada de chave
- Cancelar autorização **ATIVA** (regra: próprio autorizador ou admin)
- Visualizar histórico (conforme permissão de módulo)

### 3.2 Admin (com login)
Permissões:
- CRUD de laboratórios
- (Opcional) CRUD de chaves (se a escola precisar de múltiplas chaves por laboratório)
- CRUD de usuários e permissões por módulo (se já adotado)
- Gestão de terminais (portaria) e tokens

### 3.3 Portaria (sem login — kiosk)
Permissões:
- Consultar autorizações válidas
- Registrar **retirada** (entrega da chave)
- Registrar **devolução**
- Restrito por rede/IP e token de terminal (mesmo padrão do sistema)

---

## 4) Escopo do MVP

### 4.1 Inclui
- Cadastro de laboratórios (Admin)
- Autorização de retirada por laboratório (Autorizador)
- Consulta e registro de **RETIRADA** e **DEVOLUÇÃO** na portaria
- Regras contra “retirada duplicada sem devolução”
- Histórico do módulo com filtros básicos
- Auditoria (logs) das ações relevantes

### 4.2 Não inclui (V2)
- Integração com SUAP/AD
- Foto do aluno
- Notificações (WhatsApp/SMS/e-mail)
- QR code / código de autorização
- Controle de inventário de cópias de chaves por laboratório (se necessário pode entrar no V2)

---

## 5) Entidades e modelo de dados

> **Observação:** A aplicação já possui `students`, `users`, `terminals` e `audit_logs`. Este módulo adiciona novas tabelas.

### 5.1 `laboratorios`
Representa o laboratório ou sala com controle de chave.

Campos:
- `id` (PK)
- `nome` (UNIQUE) — ex.: “LAB INFORMÁTICA 01”
- `localizacao` (nullable) — ex.: “Bloco B”
- `ativo` (bool, default 1)
- `created_at`, `updated_at`

### 5.2 `autorizacoes_chave`
Autorização emitida pelo servidor para que o aluno possa retirar chave de um laboratório.

Campos:
- `id` (PK)
- `student_id` (FK students)
- `laboratorio_id` (FK laboratorios)
- `autorizado_por_user_id` (FK users)
- `autorizado_por_nome_snapshot` (string)
- `valid_from` (nullable) — se não informar, assume “agora”
- `valid_until` (datetime) — **obrigatório**
- `observacao` (nullable, curta)
- `status` (string) — `ATIVA | CANCELADA | EXPIRADA`
- `created_at`, `updated_at`

### 5.3 `movimentacoes_chave`
Registra eventos de retirada e devolução.

Campos:
- `id` (PK)
- `autorizacao_id` (FK autorizacoes_chave)
- `acao` (string) — `RETIRADA | DEVOLUCAO`
- `terminal_id` (FK terminals, nullable)
- `terminal_name_snapshot` (nullable)
- `realizado_em` (datetime)
- `observacao` (nullable)

### 5.4 Índices recomendados
- `autorizacoes_chave`:  
  - `INDEX(student_id, status)`  
  - `INDEX(laboratorio_id, status)`  
  - `INDEX(autorizado_por_user_id, created_at)`  
  - `INDEX(valid_until)`  
- `movimentacoes_chave`:  
  - `INDEX(autorizacao_id, realizado_em)`  
  - `INDEX(acao, realizado_em)`

---

## 6) Regras de negócio (MVP)

1) **Autorização obrigatória:** aluno só retira chave se existir autorização `ATIVA` e dentro de `valid_from/valid_until`.

2) **Validade obrigatória:** toda autorização tem `valid_until`.  
   - Sugestão padrão: “até o fim do turno” ou “até HH:MM”.

3) **Retirada única sem devolução:**  
   - Não permitir registrar `RETIRADA` se já existe uma `RETIRADA` sem `DEVOLUCAO` para a mesma autorização.

4) **Devolução só se houve retirada:**  
   - Não permitir `DEVOLUCAO` se não existe `RETIRADA` pendente.

5) **Expiração automática:**  
   - Autorização `ATIVA` vira `EXPIRADA` quando `valid_until < now()`.

6) **Cancelamento:**  
   - Permitido somente em `ATIVA`.
   - Padrão: o autorizador cancela as próprias autorizações; admin cancela qualquer.

7) **Auditoria obrigatória:**  
   - Criar logs em `audit_logs` para:
     - criar autorização
     - cancelar autorização
     - registrar retirada
     - registrar devolução

---

## 7) Fluxos (jornadas)

### 7.1 Autorizador — Criar autorização
1. Autorizador abre “Autorizar Chave”
2. Busca aluno por matrícula (preferencial) ou nome
3. Se aluno não existir, cadastra “por demanda”
4. Seleciona laboratório
5. Define validade (até HH:MM)
6. (Opcional) Observação
7. Salva → autorização `ATIVA`
8. Portaria passa a enxergar a autorização (na lista do dia e/ou busca por aluno)

### 7.2 Portaria — Registrar retirada (entrega da chave)
1. Aluno chega e se identifica
2. Portaria busca aluno por matrícula/nome
3. Tela mostra autorizações `ATIVAS` do aluno (com laboratório e validade)
4. Portaria seleciona o laboratório autorizado
5. Clica **Registrar retirada**
6. Sistema grava `RETIRADA` com data/hora e terminal
7. UI passa a mostrar status “RETIRADA PENDENTE DEVOLUÇÃO” e habilita botão “Registrar devolução”

### 7.3 Portaria — Registrar devolução
1. Aluno devolve a chave
2. Portaria busca o aluno (ou filtra por “pendentes”)
3. Sistema mostra retiradas pendentes
4. Portaria clica **Registrar devolução**
5. Sistema grava `DEVOLUCAO` com data/hora e terminal
6. UI marca como finalizado

---

## 8) Telas e requisitos de UI/UX (design)

### 8.1 Padrão visual (seguir o design da aplicação)
- UI moderna, limpa, com **Tailwind**
- Componentes:
  - cards com borda suave, sombra leve
  - botões grandes (principalmente portaria)
  - status em “chips” (ATIVA / EXPIRADA / CANCELADA / PENDENTE)
- Layout responsivo:
  - Desktop: 2 colunas quando possível
  - Mobile: 1 coluna
- Portaria (kiosk):
  - fonte maior
  - poucos elementos
  - foco em rapidez (1–2 cliques)

### 8.2 Tela: Admin — Laboratórios
URL sugerida: `/admin/laboratorios`
- Tabela com:
  - nome, localização, ativo, ações
- Ações:
  - criar
  - editar
  - ativar/desativar

### 8.3 Tela: Autorizador — Autorizar Chave
URL sugerida: `/chaves/autorizar`
Componentes:
- Campo matrícula (atalho Enter)
- Busca por nome (fallback)
- Card do aluno: nome + curso/série/turma
- Dropdown laboratório
- Validade:
  - opção “até HH:MM”
  - presets: 30/60/120 min (opcional)
- Observação curta (opcional)
- Botão “Autorizar”

Estados:
- aluno não encontrado → abrir modal “Cadastrar aluno”
- autorização criada → toast sucesso + mostrar autorização recém-criada
- tentativa duplicada → mostrar aviso e listar autorizações ativas do mesmo laboratório/aluno

### 8.4 Tela: Portaria — Controle de Chaves (Kiosk)
URL sugerida: `/portaria/chaves`
- Filtro padrão: HOJE
- Seções:
  - Busca rápida (matrícula/nome)
  - Lista de autorizações do aluno (quando pesquisado)
  - Lista de “pendentes de devolução” (atalho para a portaria)

Lista de autorização:
- Laboratório
- Autorizado por
- Validade
- Status chip (ATIVA/EXPIRADA/CANCELADA)
- Botões:
  - **REGISTRAR RETIRADA** (só se ATIVA e sem retirada pendente)
  - **REGISTRAR DEVOLUÇÃO** (só se há retirada pendente)

### 8.5 Tela: Histórico (Módulo Chaves)
URL sugerida: `/chaves/historico`
- Filtros:
  - período
  - laboratório
  - aluno (matrícula/nome)
  - autorizador
  - status (ATIVA/EXPIRADA/CANCELADA) e “pendente devolução”
- Exibição em formato humano:
  - “Aluno X — LAB Y — autorizado por Z em … — retirada em … — devolução em …”
- Paginação

---

## 9) Segurança

### 9.1 Portaria
- sem login
- protegido por:
  - token do terminal (header/cookie)
  - restrição por IP/sub-rede (se configurado)
- endpoints críticos (retirada/devolução) exigem token válido

### 9.2 Autorizadores/Admin
- login por sessão
- CSRF token em POST
- RBAC por módulo (se já adotado), caso contrário por role

---

## 10) APIs internas e rotas (sugestão)

### 10.1 Autorizador (auth)
- `GET /chaves/autorizar`
- `POST /chaves/autorizacoes` (criar autorização)
- `POST /chaves/autorizacoes/{id}/cancelar`
- `GET /chaves/historico`

### 10.2 Admin (auth + role/module)
- `GET /admin/laboratorios`
- `POST /admin/laboratorios`
- `PUT /admin/laboratorios/{id}`
- `POST /admin/laboratorios/{id}/toggle`

### 10.3 Portaria (kiosk)
- `GET /portaria/chaves`
- `GET /portaria/chaves/dados?date=YYYY-MM-DD`
- `POST /portaria/chaves/autorizacoes/{id}/retirar`
- `POST /portaria/chaves/autorizacoes/{id}/devolver`

---

## 11) Critérios de aceite (MVP)

1) Admin consegue cadastrar laboratórios e ativar/desativar.
2) Autorizador consegue criar autorização válida para um aluno e laboratório.
3) Portaria busca aluno e visualiza autorizações ativas.
4) Portaria registra retirada com data/hora e terminal.
5) Portaria registra devolução e impede duplicidade de retirada sem devolução.
6) Histórico exibe corretamente autorização + retirada + devolução.
7) Auditoria registra as quatro ações: criar, cancelar, retirar, devolver.

---

## 12) Plano de implementação (backlog por épico)

### Épico A1 — Admin Laboratórios
- A1.1 Criar tabela `laboratorios`
- A1.2 CRUD básico com UI Tailwind
- A1.3 Validação de nome único + ativo

### Épico A2 — Autorizações
- A2.1 Criar tabela `autorizacoes_chave`
- A2.2 Tela “Autorizar” com cadastro sob demanda do aluno
- A2.3 Cancelamento com RBAC
- A2.4 Expiração automática (no listar e/ou scheduler)

### Épico A3 — Portaria Chaves (kiosk)
- A3.1 Tela kiosk e busca aluno
- A3.2 Registrar retirada (RETIRADA)
- A3.3 Registrar devolução (DEVOLUCAO)
- A3.4 Listar pendentes de devolução

### Épico A4 — Histórico e Auditoria
- A4.1 Tabela `movimentacoes_chave`
- A4.2 Página histórico com filtros
- A4.3 Audit logs nas ações

---

## 13) DDL (MySQL) — Módulo A

```sql
CREATE TABLE laboratorios (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  localizacao VARCHAR(120) NULL,
  ativo TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  UNIQUE KEY uk_labs_nome (nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE autorizacoes_chave (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id BIGINT UNSIGNED NOT NULL,
  laboratorio_id BIGINT UNSIGNED NOT NULL,
  autorizado_por_user_id BIGINT UNSIGNED NOT NULL,
  autorizado_por_nome_snapshot VARCHAR(120) NOT NULL,
  valid_from DATETIME NULL,
  valid_until DATETIME NOT NULL,
  observacao VARCHAR(255) NULL,
  status VARCHAR(20) NOT NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  KEY idx_auth_student_status (student_id, status),
  KEY idx_auth_lab_status (laboratorio_id, status),
  KEY idx_auth_user_created (autorizado_por_user_id, created_at),
  KEY idx_auth_valid_until (valid_until),
  CONSTRAINT fk_auth_student FOREIGN KEY (student_id) REFERENCES students(id),
  CONSTRAINT fk_auth_lab FOREIGN KEY (laboratorio_id) REFERENCES laboratorios(id),
  CONSTRAINT fk_auth_user FOREIGN KEY (autorizado_por_user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE movimentacoes_chave (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  autorizacao_id BIGINT UNSIGNED NOT NULL,
  acao VARCHAR(20) NOT NULL,
  terminal_id BIGINT UNSIGNED NULL,
  terminal_name_snapshot VARCHAR(120) NULL,
  realizado_em DATETIME NOT NULL,
  observacao VARCHAR(255) NULL,
  KEY idx_mov_auth_time (autorizacao_id, realizado_em),
  KEY idx_mov_acao_time (acao, realizado_em),
  CONSTRAINT fk_mov_auth FOREIGN KEY (autorizacao_id) REFERENCES autorizacoes_chave(id),
  CONSTRAINT fk_mov_terminal FOREIGN KEY (terminal_id) REFERENCES terminals(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
```

---

## 14) Prompt (Cursor) — Implementar Módulo A (auditar e criar)

```text
Audite o projeto e verifique se já existe um módulo de controle de chaves de laboratório. Se não existir ou estiver incompleto, implemente o Módulo A (Controle de Chaves) conforme este PRD:

- Criar tabelas MySQL: laboratorios, autorizacoes_chave, movimentacoes_chave com índices e FKs.
- Admin: CRUD de laboratorios (/admin/laboratorios) com UI Tailwind.
- Autorizador (auth): tela /chaves/autorizar com:
  - busca por matrícula/nome
  - cadastro do aluno por demanda se não existir
  - dropdown laboratório
  - validade (até HH:MM)
  - observação (opcional)
  - botão autorizar
  - cancelar autorização ATIVA (com RBAC)
  - gravar audit_logs em criar/cancelar
- Portaria kiosk (sem login): /portaria/chaves com:
  - busca por aluno
  - lista autorizações ATIVAS e status
  - botões Registrar Retirada e Registrar Devolução
  - regras: não permitir retirada duplicada sem devolução; não permitir devolução sem retirada
  - registrar movimentacoes_chave e audit_logs
  - endpoints POST protegidos por token do terminal + IP (mesmo padrão já existente)
- Histórico: /chaves/historico com filtros básicos e paginação.
- Garantir que autorizações expiram (ATIVA -> EXPIRADA) quando valid_until < now.
Ao final, listar os arquivos criados/alterados e como testar manualmente.
```

---

## 15) Checklist de teste manual (MVP)

1. **Admin**
   - Criar laboratório “LAB INFORMÁTICA 01”.
   - Confirmar que aparece na lista e pode ativar/desativar.

2. **Autorizador**
   - Acessar `/chaves/autorizar`.
   - Digitar matrícula inexistente → cadastrar aluno por demanda.
   - Autorizar para LAB com validade até 18:00.
   - Ver autorização criada (status ATIVA).

3. **Portaria**
   - Acessar `/portaria/chaves`.
   - Buscar aluno → ver autorização ATIVA.
   - Clicar “Registrar retirada” → grava e mostra devolução disponível.
   - Clicar “Registrar devolução” → finaliza.

4. **Bloqueios**
   - Tentar retirar duas vezes sem devolver → deve bloquear.
   - Tentar devolver sem retirar → deve bloquear.
   - Esperar expirar `valid_until` → autorização vira EXPIRADA e não permite retirar.

5. **Histórico**
   - Acessar `/chaves/historico` e filtrar por laboratório e período.
   - Conferir texto humano com timestamps.

---

**Fim do PRD (Módulo A)**
