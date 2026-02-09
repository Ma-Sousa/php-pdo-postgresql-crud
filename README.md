# PHP + PDO + PostgreSQL – Customers CRUD (OOP)

Customers CRUD built with **PHP + PDO + PostgreSQL**, refactored to a small **OOP** structure with a Repository layer.

## Features
- Full CRUD (Create, Read, Update, Delete)
- Search by **name** or **email**
- **Pagination** (COUNT + LIMIT/OFFSET)
- Session **flash messages**
- **CSRF protection** on forms (create/edit/delete)
- SQL centralized in `CustomerRepository.php`
- PDO connection centralized in `Database.php`

## How it works (high level)
- Every page starts with `require_once "bootstrap.php"`.
- `bootstrap.php` starts the session, loads helpers (flash/csrf/validator), reads `config.php`, builds a PDO instance via `Database`, and creates `CustomerRepository`.
- Pages (`index.php`, `create.php`, `edit.php`, `delete.php`) only orchestrate request/response and call repository methods.

## Project Structure
- `bootstrap.php` – app bootstrap (session, helpers, config, DB + repository)
- `Database.php` – PDO connection class
- `CustomerRepository.php` – all SQL queries (find/create/update/delete/count/pagination)
- `index.php` – list + search + pagination
- `create.php` – create customer (validation + CSRF)
- `edit.php` – edit customer (validation + CSRF)
- `delete.php` – delete customer (POST + CSRF)
- `validator.php` – basic validation rules
- `flash.php` – session flash helpers
- `csrf.php` – CSRF helpers
- `helpers.php` – small helper functions
- `header.php` / `footer.php` – layout partials
- `style.css` – UI styles
- `app.js` – small UI behavior (if any)
- `config.example.php` – example config (copy to `config.php`)

## Tech Stack
- PHP (XAMPP)
- PostgreSQL
- PDO
- HTML + CSS (+ optional JS)

## Requirements
- XAMPP (Apache + PHP)
- PostgreSQL running locally (default port 5432)
- PHP extensions enabled:
  - `pdo_pgsql`
  - `pgsql`

## Local Setup (Windows / XAMPP)

1) Copy the project to:
```text
C:\xampp\htdocs\PHP\
```
2. Create your local config:
- Copy config.example.php
- Rename to config.php
- Set your PostgreSQL credentials in config.php

3. Create the database table:
``` sql
CREATE TABLE customers (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) UNIQUE,
  created_at TIMESTAMP DEFAULT NOW()
);
```
4. Start Apache using XAMPP.

5. Open in your browser:
  ```text
    http://localhost/PHP/
```