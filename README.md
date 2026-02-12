# PHP + PDO + PostgreSQL – Customers CRUD (OOP + Repository + Tests)

CRUD de **Customers** feito com **PHP + PDO + PostgreSQL**, organizado em **OOP** com **Repository Pattern** e **testes de integração com PHPUnit**.

## Features
- CRUD completo (Create, Read, Update, Delete)
- Busca por **name** ou **email** (ILIKE)
- **Paginação** (COUNT + LIMIT/OFFSET)
- Email **opcional** (salva como `NULL` quando vazio)
- `created_at` automático (`DEFAULT NOW()`)
- Session **flash messages**
- **CSRF protection** (create/edit/delete)
- SQL centralizado em `src/CustomerRepository.php`
- Conexão PDO centralizada em `src/Database.php`
- Testes de integração com rollback por transaction

---

## Project Structure
- `bootstrap.php` – inicia sessão, carrega helpers, config, DB + repository
- `src/Database.php` – classe de conexão PDO
- `src/CustomerRepository.php` – queries (find/create/update/delete/count/getPage)
- `tests/CustomerRepositoryTest.php` – testes de integração (Postgres + rollback)
- `database/schema.sql` – schema da tabela `customers`
- `index.php` – listagem + busca + paginação
- `create.php` – criação (validação + CSRF)
- `edit.php` – edição (validação + CSRF)
- `delete.php` – delete (POST + CSRF)
- `validator.php` – validações
- `flash.php` – flash messages
- `csrf.php` – CSRF helpers
- `helpers.php` – helpers gerais
- `partials/header.php` / `partials/footer.php` – layout
- `style.css` – estilos
- `app.js` – JS opcional

---

## Tech Stack
- PHP 8+
- PostgreSQL
- PDO (pgsql)
- PHPUnit (via Composer)
- HTML + CSS (+ JS opcional)

---

## Requirements
- PHP 8+ (XAMPP ok)
- PostgreSQL local
- Composer
- Extensões PHP:
  - `pdo_pgsql`
  - `pgsql`

---

## Local Setup (Windows / XAMPP)

1) Coloque o projeto em:
```text
C:\xampp\htdocs\PHP\
```

2) Config do projeto:
- Copie `config.example.php` → `config.php`
- Ajuste credenciais do Postgres

3) Crie a tabela rodando o schema:
- Abra o arquivo `database/schema.sql`
- Rode no seu banco (pgAdmin / psql)

4) Start Apache no XAMPP

5) Acesse:
```text
http://localhost/PHP/
```

---

## Tests (PHPUnit)

### Banco de testes
Os testes usam um banco separado (recomendado), definido em `config.test.php`.

Você precisa criar o DB de testes e a tabela nele também:

1) Crie o DB de testes (exemplo):
```sql
CREATE DATABASE php_pdo_db_test;
```

2) Rode o schema também nele (`database/schema.sql`).

3) Ajuste `config.test.php` com as credenciais corretas.

### Rodar testes
```bash
composer test
```

> Os testes abrem uma transaction no `setUp()` e fazem rollback no `tearDown()` para manter o banco limpo a cada teste.

---

## Notes / Decisions
- **Email é opcional**: se vier vazio (`""`), o repository transforma em `NULL` antes de salvar.
- `created_at` é automático via `DEFAULT NOW()`.
- `update()` e `delete()` retornam `bool` usando `rowCount()` para indicar se afetou 1 registro.

---

## Roadmap (next)
- GitHub Actions rodando `composer test`
- Melhorar UI/UX e mensagens de erro
- Migrar para Laravel (CRUD + validações + migrations + tests)
