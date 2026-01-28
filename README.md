# PHP + PDO + PostgreSQL – Customers CRUD

A CRUD application built with **PHP + PDO + PostgreSQL** to practice backend fundamentals (secure DB access, clean structure, pagination, and basic UI).

## Features
- Full CRUD (Create, Read, Update, Delete)
- Search customers by **name** or **email**
- **Pagination** with total count (COUNT + LIMIT/OFFSET)
- Session **flash messages** (success/error)
- **CSRF protection** on forms (create/edit/delete)
- SQL isolated in a single file (`customers.php`) to keep pages clean
- Simple UI with HTML + CSS

## Project Structure
- `index.php` – list + search + pagination
- `create.php` – create customer (validated + CSRF)
- `edit.php` – edit customer (validated + CSRF)
- `delete.php` – delete customer (CSRF)
- `customers.php` – repository functions (all SQL)
- `db.php` – database connection (PDO)
- `flash.php` – session flash helpers
- `csrf.php` – CSRF helpers
- `partials/` – layout partials (header/footer)
- `style.css` – UI styles
- `config.example.php` – example config (copy to `config.php`)

## Tech Stack
- PHP (XAMPP)
- PostgreSQL
- PDO
- HTML + CSS

## Requirements
- XAMPP (Apache + PHP)
- PostgreSQL running locally (default port 5432)
- PHP extensions enabled:
  - `pdo_pgsql`
  - `pgsql`

## Local Setup (Windows / XAMPP)

1. Copy the project to:
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