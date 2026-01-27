<?php
require __DIR__ . "/db.php";

// Flash messages (coming from redirects)
$success = $_GET["success"] ?? "";
$flashMessage = "";

if ($success === "created") $flashMessage = "Customer created successfully.";
if ($success === "updated") $flashMessage = "Customer updated successfully.";
if ($success === "deleted") $flashMessage = "Customer deleted successfully.";

// Search
$q = trim($_GET["q"] ?? "");

if ($q !== "") {
    $stmt = $pdo->prepare("
        SELECT id, name, email, created_at
        FROM customers
        WHERE name ILIKE :q OR email ILIKE :q
        ORDER BY id
    ");
    $stmt->execute(["q" => "%{$q}%"]);
} else {
    $stmt = $pdo->query("SELECT id, name, email, created_at FROM customers ORDER BY id");
}

$customers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customers</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">

    <header class="header">
      <h1>Customers</h1>
      <a class="btn" href="create.php">+ New Customer</a>
    </header>

    <?php if ($flashMessage): ?>
      <div class="notice success"><?= htmlspecialchars($flashMessage) ?></div>
    <?php endif; ?>

    <div class="card">
      <form method="GET" class="search">
        <input
          type="text"
          name="q"
          placeholder="Search by name or email..."
          value="<?= htmlspecialchars($q) ?>"
        >
        <button class="btn btn-secondary" type="submit">Search</button>
        <?php if ($q !== ""): ?>
          <a class="btn btn-secondary" href="index.php">Clear</a>
        <?php endif; ?>
      </form>

      <table class="table">
        <thead>
          <tr>
            <th style="width: 180px;">Actions</th>
            <th style="width: 70px;">ID</th>
            <th>Name</th>
            <th>Email</th>
            <th style="width: 220px;">Created At</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($customers as $c): ?>
            <tr>
            <td>
                <a class="btn btn-secondary" href="edit.php?id=<?= (int)$c["id"] ?>">Edit</a>

                <form method="POST" action="delete.php" class="inline-form">
                <input type="hidden" name="id" value="<?= (int)$c["id"] ?>">
                <button
                    class="btn btn-danger"
                    type="submit"
                    onclick="return confirm('Delete this customer?')"
                >
                    Delete
                </button>
                </form>
            </td>
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
