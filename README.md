# PHP + PDO + PostgreSQL – CRUD Application

A simple backend project built with PHP and PostgreSQL to demonstrate secure CRUD operations using PDO and prepared statements.

## Features
- List customers
- Create, edit and delete customers
- Search customers by name or email
- Flash messages for create/update/delete actions
- Secure database access using prepared statements (PDO)

## Tech Stack
- PHP (XAMPP)
- PostgreSQL
- PDO (PHP Data Objects)
- HTML + CSS (basic UI)

## Requirements
- XAMPP (Apache + PHP)
- PostgreSQL running locally (default port 5432)
- PHP extensions enabled in XAMPP:
  - `pdo_pgsql`
  - `pgsql`

## Local Setup

1. Copy this project into XAMPP:

```text
C:\xampp\htdocs\PHP\
```
2. Create the configuration file:
- Copy config.example.php
- Rename it to config.php
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

