<?php
declare(strict_types=1);

namespace App;

use PDO;

final class CustomerRepository
{
    public function __construct(private PDO $pdo) {}

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT id, name, email, created_at FROM customers WHERE id = :id");
        $stmt->execute(["id" => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(string $name, ?string $email): int
    {
        $email = ($email === null || trim($email) === '') ? null : $email;

        $stmt = $this->pdo->prepare("
        INSERT INTO customers (name,email)
        VALUES (:name, :email)
        RETURNING id
        ");
        $stmt->execute(["name" => $name, "email" => $email]);
        return (int)$stmt->fetchColumn();
    }

    public function update(int $id, string $name, ?string $email): bool
    {
        $email = ($email === null || trim($email) === '') ? null : $email;

        $stmt = $this->pdo->prepare("
        UPDATE customers
        SET name = :name, email = :email
        WHERE id = :id
        ");
        $stmt->execute(["id" => $id, "name" => $name, "email" => $email]);

        return $stmt->rowCount() === 1;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM customers WHERE id = :id");
        $stmt->execute(["id" => $id]);

        return $stmt->rowCount() === 1;
    }

    public function count(string $q = ""): int
    {
        $q = trim($q);

        if ($q !== "") {
            $stmt = $this->pdo->prepare("
            SELECT COUNT(*)
            FROM customers
            WHERE name ILIKE :q OR email ILIKE :q
            ");
            $stmt->execute(["q" => "%{$q}%"]);
            return (int)$stmt->fetchColumn();
        }

        $stmt = $this->pdo->query("SELECT COUNT(*) FROM customers");
        return (int)$stmt->fetchColumn();
    }

    public function getPage(string $q, int $limit, int $offset): array
    {
        $q = trim($q);
        $limit = max(1, min($limit, 100));
        $offset = max(0, $offset);

        if ($q !== "") {
            $stmt = $this->pdo->prepare("
            SELECT id, name, email, created_at
            FROM customers
            WHERE name ILIKE :q OR email ILIKE :q
            ORDER BY id DESC 
            LIMIT :limit OFFSET :offset
            ");
            $stmt->bindValue("q", "%{$q}%", PDO::PARAM_STR);
            $stmt->bindValue("limit", $limit, PDO::PARAM_INT);
            $stmt->bindValue("offset", $offset, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $stmt = $this->pdo->prepare("
            SELECT id, name, email, created_at
            FROM customers
            ORDER BY id DESC
            LIMIT :limit OFFSET :offset
            ");
            $stmt->bindValue("limit", $limit, PDO::PARAM_INT);
            $stmt->bindValue("offset", $offset, PDO::PARAM_INT);
            $stmt->execute();
        }

        return $stmt->fetchAll();
    }
}