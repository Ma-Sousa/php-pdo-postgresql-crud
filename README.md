# PHP + PDO + PostgreSQL (Mini CRUD)

A small project created to practice:
- PHP (XAMPP)
- PostgreSQL
- PDO (prepared statements)
- Basic Create + Read operations

## Features
- List customers
- Create a new customer
- Secure inserts using prepared statements

## Requirements
- XAMPP (Apache + PHP)
- PostgreSQL running locally (default port 5432)
- PHP extensions enabled in XAMPP:
  - `pdo_pgsql`
  - `pgsql`

## Setup (Local)

1. Copy this project into XAMPP:
   `C:\xampp\htdocs\PHP\`

2. Create `config.php` from `config.example.php`:
   - Copy `config.example.php`
   - Rename to `config.php`
   - Add your real database password in `config.php`

3. Create the table in PostgreSQL:

```sql
CREATE TABLE customers (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) UNIQUE,
  created_at TIMESTAMP DEFAULT NOW()
);
