<?php
require __DIR__ . "/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name  = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");

    if ($name === "") {
        $error = "Name is required.";
    } else {
        try {
            $sql = "INSERT INTO customers (name, email) VALUES (:name, :email)";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                "name"  => $name,
                "email" => $email !== "" ? $email : null,
            ]);

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
  <title>New Customer</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <header class="header">
      <h1>New Customer</h1>
      <a class="btn btn-secondary" href="index.php">Back</a>
    </header>

    <div class="card">
      <?php if ($error): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" class="form">
        <div class="field">
          <label for="name">Name</label>
          <input id="name" type="text" name="name" required />
        </div>

        <div class="field">
          <label for="email">Email (optional)</label>
          <input id="email" type="email" name="email" />
        </div>

        <button class="btn" type="submit">Save</button>
      </form>
    </div>
  </div>
</body>
</html>
