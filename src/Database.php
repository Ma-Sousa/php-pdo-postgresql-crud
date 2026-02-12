<?php
declare(strict_types=1);

namespace App;

use PDO;
use PDOException;

final class Database
{
    private PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = "pgsql:host={$config["host"]};port={$config["port"]};dbname={$config["dbname"]}";

        $this->pdo = new PDO($dsn, $config["user"], $config["password"], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }
}