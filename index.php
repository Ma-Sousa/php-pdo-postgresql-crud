<?php
require __DIR__ . "/db.php";

$stmt = $pdo->query("SELECT id, name, email, created_at FROM customers ORDER BY id");
$customers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customers</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <header class="header">
      <h1>Customers</h1>
      <a class="btn" href="create.php">+ New Customer</a>
    </header>

    <div class="card">
      <table class="table">
        <thead>
          <tr>
            <th style="width: 70px;">ID</th>
            <th>Name</th>
            <th>Email</th>
            <th style="width: 200px;">Created At</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($customers as $c): ?>
            <tr>
              <td><?= htmlspecialchars((string)$c["id"]) ?></td>
              <td><?= htmlspecialchars($c["name"]) ?></td>
              <td><?= htmlspecialchars($c["email"] ?? "") ?></td>
              <td><?= htmlspecialchars((string)$c["created_at"]) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <?php if (count($customers) === 0): ?>
        <p class="muted">No customers found.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
