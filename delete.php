<?php
require __DIR__ . "/db.php";
require_once __DIR__ . "/customers.php";
require_once __DIR__ . "/flash.php";
require_once __DIR__ . "/csrf.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  die("Invalid request.");
}

csrf_verify($_POST["csrf"] ?? null);

$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
if ($id <= 0) {
  die("ID not provided.");
}

deleteCustomer($pdo, $id);

flash_set("success", "Customer deleted successfully.");
header("Location: index.php");
exit;
