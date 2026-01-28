<?php
require __DIR__ . "/db.php";
require_once __DIR__ . "/customers.php";
require_once __DIR__ . "/flash.php";
require_once __DIR__ . "/csrf.php";

$error = "";
$name = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  csrf_verify($_POST["csrf"] ?? null);

  $name  = trim($_POST["name"] ?? "");
  $email = trim($_POST["email"] ?? "");

  if ($name === "") {
    $error = "Name is required.";
  } elseif (strlen($name) > 100) {
    $error = "Name must be 100 characters or less.";
  } elseif ($email !== "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Please enter a valid email.";
  } else {
    try {
      createCustomer($pdo, $name, $email !== "" ? $email : null);
      flash_set("success", "Customer created successfully.");
      header("Location: index.php");
      exit;
    } catch (PDOException $e) {
      $error = "Error inserting record: " . $e->getMessage();
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Customer</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Create Customer</h1>
      <a class="btn btn-secondary" href="index.php">Back</a>
    </div>

    <?php if ($error): ?>
      <div class="notice error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card">
      <form class="form" method="POST" action="create.php">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">

        <div class="field">
          <label for="name">Name *</label>
          <input id="name" type="text" name="name" required maxlength="100" value="<?= htmlspecialchars($name) ?>">
        </div>

        <div class="field">
          <label for="email">Email</label>
          <input id="email" type="email" name="email" maxlength="120" value="<?= htmlspecialchars($email) ?>">
        </div>

        <button class="btn" type="submit">Create</button>
      </form>
    </div>
  </div>
</body>
</html>
