<?php 

if (session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once __DIR__ . "/helpers.php";
require_once __DIR__ . "/flash.php";
require_once __DIR__ . "/csrf.php";
require_once __DIR__ . "/src/CustomerRepository.php";
require_once __DIR__ . "/src/Database.php";

use App\Database;
use App\CustomerRepository;

$config = require __DIR__ . "/config.php";

$db = new Database($config);
$pdo = $db->pdo();
$customersRepo = new CustomerRepository($pdo);
