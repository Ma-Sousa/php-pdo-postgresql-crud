<?php
// customers.php

function getCustomerById(PDO $pdo, int $id): ?array {
  $stmt = $pdo->prepare("SELECT id, name, email FROM customers WHERE id = :id");
  $stmt->execute(["id" => $id]);
  $row = $stmt->fetch();
  return $row ?: null;
}

function createCustomer(PDO $pdo, string $name, ?string $email): int {
  $stmt = $pdo->prepare("
    INSERT INTO customers (name, email)
    VALUES (:name, :email)
    RETURNING id
  ");
  $stmt->execute(["name" => $name, "email" => $email]);
  return (int)$stmt->fetchColumn();
}

function updateCustomer(PDO $pdo, int $id, string $name, ?string $email): void {
  $stmt = $pdo->prepare("
    UPDATE customers
    SET name = :name, email = :email
    WHERE id = :id
  ");
  $stmt->execute(["id" => $id, "name" => $name, "email" => $email]);
}

function deleteCustomer(PDO $pdo, int $id): void {
  $stmt = $pdo->prepare("DELETE FROM customers WHERE id = :id");
  $stmt->execute(["id" => $id]);
}

/**
 * Pagination helpers
 */
function countCustomers(PDO $pdo, string $q = ""): int {
  $q = trim($q);

  if ($q !== "") {
    $stmt = $pdo->prepare("
      SELECT COUNT(*)
      FROM customers
      WHERE name ILIKE :q OR email ILIKE :q
    ");
    $stmt->execute(["q" => "%{$q}%"]);
    return (int)$stmt->fetchColumn();
  }

  $stmt = $pdo->query("SELECT COUNT(*) FROM customers");
  return (int)$stmt->fetchColumn();
}

function getCustomersPage(PDO $pdo, string $q, int $limit, int $offset): array {
  $q = trim($q);
  $limit = max(1, min($limit, 100));
  $offset = max(0, $offset);

  if ($q !== "") {
    $stmt = $pdo->prepare("
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
    $stmt = $pdo->prepare("
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
