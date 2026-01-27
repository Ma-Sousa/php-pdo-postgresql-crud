<?php
$config = require __DIR__ . "/config.php";

$dsn = "pgsql:host={$config["host"]};port={$config["port"]};dbname={$config["dbname"]}";

try {
    $pdo = new PDO($dsn, $config["user"], $config["password"], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}
