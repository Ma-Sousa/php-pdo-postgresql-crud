<?php
require __DIR__ . "/db.php";

$id = $_GET["id"] ?? null;
$error = "";

if (!$id) {
    die("ID not provided.");
}

$stmt = $pdo->prepare("SELECT id, name, email FROM customers WHERE id = :id");
$stmt->execute(["id" => $id]);
$customer = $stmt->fetch();

if (!$customer) {
    die("Customer not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
            $sql = "UPDATE customers SET name = :name, email = :email WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                "id"    => $customer["id"],
                "name"  => $name,
                "email" => $email !== "" ? $email : null,
            ]);

            header("Location: index.php?success=updated");
            exit;
        } catch (PDOException $e) {
            $error = "Error updating customer: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Customer</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <header class="header">
      <h1>Edit Customer</h1>
      <a class="btn btn-secondary" href="index.php">Back</a>
    </header>

    <div class="card">
      <?php if ($error): ?>
        <div class="notice error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" class="form">
        <div class="field">
          <label for="name">Name</label>
          <input id="name" type="text" name="name"
                 value="<?= htmlspecialchars($customer["name"]) ?>"
                 required maxlength="100">
        </div>

        <div class="field">
          <label for="email">Email (optional)</label>
          <input id="email" type="email" name="email"
                 value="<?= htmlspecialchars($customer["email"] ?? "") ?>"
                 maxlength="120">
        </div>

        <button class="btn" type="submit">Save</button>
      </form>
    </div>
  </div>
</body>
</html>
