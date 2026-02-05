<?php

function validate_customer_input(string $nameRaw, string $emailRaw): array 
{
    $errors = [];

    $name = trim($nameRaw);
    $email = trim($emailRaw);

  if ($name === "") {
    $error["name"] = "Name is required.";
  } elseif (mb_strlen($name) > 100) {
    $error["name"] = "Name must be 100 characters or less.";
  }

  $emailOrNull = ($email === "") ? null : $email;

  return [$name, $emailOrNull, $errors];
}


function is_unique_violation(PDOException $e): bool 
{
    return ($e ->getCode() === "23505");
}

function unique_violation_field(PDOException $e): ?string
{
    $msg = strtolower($e->getMessage());

    if (str_contains($msg, "email")) return "email";
    if (str_contains($msg, "name")) return "name";

    return null;
}