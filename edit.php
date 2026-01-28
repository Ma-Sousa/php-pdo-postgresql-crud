<?php
require __DIR__ . "/db.php";
require_once __DIR__ . "/customers.php";
require_once __DIR__ . "/flash.php";
require_once __DIR__ . "/csrf.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$error = "";

if ($id <= 0) die("ID not provided.");

$customer = getCustomerById($pdo, $id);
if (!$customer) die("Customer not found.");

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
      updateCustomer($pdo, $id, $name, $email !== "" ? $email : null);
      flash_set("success", "Customer updated successfully.");
      header("Location: index.php");
      exit;
    } catch (PDOException $e) {
      $error = "Error updating customer: " . $e->getMessage();
    }
  }

  // keep values typed on error
  $customer["name"] = $name;
  $customer["email"] = $email;
}
$title = "Edit Customer";
require __DIR__ . "/partials/header.php";

?>
<div class="header">
  <h1>Edit Customer</h1>
  <a class="btn btn-secondary" href="index.php">Back</a>
</div>

<?php if ($error): ?>
  <div class="notice error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
  <form class="form" method="POST" action="edit.php?id=<?= (int)$id ?>">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">

    <div class="field">
      <label for="name">Name *</label>
      <input id="name" type="text" name="name" required maxlength="100" value="<?= htmlspecialchars($customer["name"]) ?>">
    </div>

    <div class="field">
      <label for="email">Email</label>
      <input id="email" type="email" name="email" maxlength="120" value="<?= htmlspecialchars($customer["email"] ?? "") ?>">
    </div>

    <button class="btn" type="submit">Save</button>
  </form>
</div>

<?php require __DIR__ . "/partials/footer.php"; ?>
