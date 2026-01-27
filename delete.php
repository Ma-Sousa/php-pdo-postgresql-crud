<?php
require __DIR__ . "/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$id = $_POST["id"] ?? null;

if (!$id) {
    die("ID not provided.");
}

$stmt = $pdo->prepare("DELETE FROM customers WHERE id = :id");
$stmt->execute(["id" => $id]);

header("Location: index.php?success=deleted");
exit;
