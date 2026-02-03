<?php

function csrf_token(): string {
    if (!isset($_SESSION["csrf"])) {
    $_SESSION["csrf"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf"];
}

function csrf_verify(?string $token): void {
    if (!$token || !hash_equals($_SESSION["csrf"] ?? "", $token)) {
    http_response_code(403);
    die("CSRF validation failed.");
    }
}
