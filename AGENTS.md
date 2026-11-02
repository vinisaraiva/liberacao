# AGENTS.md — Liberação (intranet de portaria e liberações)

Instruções para agentes de código (Hermes/Carcará, Claude Code, Codex e similares) que trabalham neste
repositório sem supervisão em tempo real. Leia este arquivo inteiro antes de começar qualquer tarefa.

## O projeto

Intranet de portaria: pedidos de liberação de saída de estudantes, autorizações e controle de chaves,
com uma tela de portaria em modo quiosque (sem login). Lida com dados de estudantes (LGPD).

| Pasta / arquivo | O que é |
|---|---|
| `app/`, `routes/`, `resources/` | Laravel 10 (PHP ≥ 8.1) + Vite/Tailwind |
| `database/migrations/` | Estrutura do banco MySQL 8 |
| `tests/` | PHPUnit (`Feature/` precisa de MySQL; `Unit/` não) |
| `regras/` | PRDs: padrão de módulos e módulo de chaves — siga-os em módulos novos |

## Como trabalhar

1. Nunca commitar direto na `main`: branch (`feat/…`, `fix/…`, `docs/…`, `chore/…`; agentes do Hermes usam
   `hermes/*`) → commits pequenos → PR.
2. Nunca reescrever histórico publicado (sem force push, rebase ou amend de commits enviados).
3. Nunca fazer merge do próprio PR. O merge é do Vinícius ou do auto-merge do Hermes (`automerge-apps.py`),
   quando estiver ligado para esta aplicação em `ci-apps.json`: branch `hermes/*`, revisão "Revisão: Marina …
   aprovada", Fiscal ✅ no commit atual, entre 7h e 22h (Bahia), e o PR não toca `database/migrations/`,
   autenticação (`app/Http/Controllers/AuthController.php`, `config/auth.php`), `.github/` nem este arquivo.
4. Uma tarefa por branch/PR; sem refatorações não pedidas junto da entrega.
5. Siga o estilo do código ao redor e os PRDs de `regras/` (comentários e textos em português).
6. Mensagens de commit: `tipo(escopo): resumo`.

## Segurança (inegociável)

- Nunca commitar `.env`, senhas, chaves ou dados reais de estudantes e servidores.
- Toda rota que altera dados exige autenticação e autorização; a tela de portaria (quiosque) só mostra o
  mínimo necessário e não expõe dados pessoais além do nome e da autorização.
- Mudanças de banco: só por migration versionada e revisada; nunca SQL direto na produção.

## Antes de dizer "pronto"

Os mesmos passos que o Fiscal roda (`.github/workflows/quality-gates.yml`):

```bash
composer install --no-interaction --prefer-dist
php artisan test --testsuite=Unit
npm install && npm run build
```

Os testes de `tests/Feature` precisam de MySQL (veja `phpunit.xml`): rode-os localmente quando mexer em fluxo.
Na descrição do PR: o que mudou, por quê, o que foi testado e o que não foi.

## Quando parar e perguntar

- Qualquer mudança em autenticação, permissões, banco ou dados pessoais.
- Pedido ambíguo ou decisão de produto não registrada.
