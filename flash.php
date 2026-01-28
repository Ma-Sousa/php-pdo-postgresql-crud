<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function flash_set(string $type, string $message): void {
    $_SESSION["flash"] = ["type" => $type, "message" => $message];
}

function flash_get(): ?array {
    if (!isset($_SESSION["flash"])) return null;
    $flash = $_SESSION["flash"];
    unset($_SESSION["flash"]);
    return $flash;
}
