<?php
require __DIR__ . "/db.php";
require_once __DIR__ . "/customers.php";
require_once __DIR__ . "/flash.php";
require_once __DIR__ . "/csrf.php";

$q = trim($_GET["q"] ?? "");
$customers = getCustomers($pdo, $q);
$flash = flash_get();

$title = "Customers";
require __DIR__ . "/partials/header.php";
?>

<div class="header">
  <h1>Customers</h1>
  <a class="btn" href="create.php">+ New Customer</a>
</div>

<?php if ($flash): ?>
  <div class="notice <?= $flash["type"] === "success" ? "success" : "error" ?>">
    <?= htmlspecialchars($flash["message"]) ?>
  </div>
<?php endif; ?>

<form class="search" method="GET" action="index.php">
  <input type="text" name="q" placeholder="Search by name or email..." value="<?= htmlspecialchars($q) ?>">
  <button class="btn btn-secondary" type="submit">Search</button>
</form>

<div class="card">
  <table class="table">
    <thead>
      <tr>
        <th>Actions</th>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Created</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($customers as $c): ?>
        <tr>
          <td>
            <a class="btn btn-secondary" href="edit.php?id=<?= (int)$c["id"] ?>">Edit</a>

            <form method="POST" action="delete.php" class="inline-form" onsubmit="return confirm('Delete this customer?');">
              <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
              <input type="hidden" name="id" value="<?= (int)$c["id"] ?>">
              <button class="btn btn-danger" type="submit">Delete</button>
            </form>
          </td>
          <td><?= (int)$c["id"] ?></td>
          <td><?= htmlspecialchars($c["name"]) ?></td>
          <td><?= htmlspecialchars($c["email"] ?? "") ?></td>
          <td><?= htmlspecialchars($c["created_at"]) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <?php if (count($customers) === 0): ?>
    <p class="muted">No customers found.</p>
  <?php endif; ?>
</div>

<?php require __DIR__ . "/partials/footer.php"; ?>
