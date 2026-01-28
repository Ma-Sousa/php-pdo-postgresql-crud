<?php
// customers.php

function getCustomers(PDO $pdo, string $q = ""): array {
  $q = trim($q);

  if ($q !== "") {
    $stmt = $pdo->prepare("
      SELECT id, name, email, created_at
      FROM customers
      WHERE name ILIKE :q OR email ILIKE :q
      ORDER BY id DESC
    ");
    $stmt->execute(["q" => "%{$q}%"]);
  } else {
    $stmt = $pdo->query("
      SELECT id, name, email, created_at
      FROM customers
      ORDER BY id DESC
    ");
  }

  return $stmt->fetchAll();
}

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
