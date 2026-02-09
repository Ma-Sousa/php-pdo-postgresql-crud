<?php
require_once __DIR__ . "/bootstrap.php";

$q = trim($_GET["q"] ?? "");

$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$page = max(1, $page);

$limit = 10;
$total = countCustomers($pdo, $q);

$totalPages = max(1, (int)ceil($total / $limit));
$page = min($page, $totalPages);

$offset = ($page - 1) * $limit;

$customers = $customersRepo->getPage($q, $limit, $offset);
$flash = flash_get();

$from = $total === 0 ? 0 : $offset + 1;
$to = min($offset + $limit, $total);

function buildPageLink(int $p, string $q): string {
  $params = ["page" => $p];
  if ($q !== "") $params["q"] = $q;
  return "index.php?" . http_build_query($params);
}

$title = "Customers";
require __DIR__ . "/partials/header.php";
?>

<div class="header">
  <h1>Customers</h1>
  <a class="btn" href="create.php">+ New Customer</a>
</div>

<?php if ($flash): ?>
  <div class="notice <?= $flash["type"] === "success" ? "success" : "error" ?>">
    <?= e($flash["message"]) ?>
  </div>
<?php endif; ?>

<form class="search" method="GET" action="index.php">
  <input type="hidden" name="page" value="1">
  <input type="text" name="q" placeholder="Search by name or email..." value="<?= e($q) ?>">
  <button class="btn btn-secondary" type="submit">Search</button>
</form>

<div class="pagination-bar">
  <div class="pagination-info">
    Showing <?= $from ?>–<?= $to ?> of <?= $total ?> results
  </div>

  <div class="pagination-controls">
    <?php if ($page > 1): ?>
      <a class="btn btn-secondary" href="<?= e(buildPageLink($page - 1, $q)) ?>">Prev</a>
    <?php else: ?>
      <span class="btn btn-secondary disabled">Prev</span>
    <?php endif; ?>

    <span class="pagination-page">Page <?= $page ?> / <?= $totalPages ?></span>

    <?php if ($page < $totalPages): ?>
      <a class="btn btn-secondary" href="<?= e(buildPageLink($page + 1, $q)) ?>">Next</a>
    <?php else: ?>
      <span class="btn btn-secondary disabled">Next</span>
    <?php endif; ?>
  </div>
</div>

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

            <form method="POST" action="delete.php" class="inline-form">
              <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
              <input type="hidden" name="id" value="<?= (int)$c["id"] ?>">
              <button type="button" class="btn btn-danger js-delete">Delete</button>
            </form>
          </td>

          <td><?= (int)$c["id"] ?></td>
          <td><?= e($c["name"]) ?></td>
          <td><?= e($c["email"] ?? "") ?></td>
          <td><?= e($c["created_at"]) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <?php if (count($customers) === 0): ?>
    <p class="muted">No customers found.</p>
  <?php endif; ?>
</div>

<?php require __DIR__ . "/partials/footer.php"; ?>
