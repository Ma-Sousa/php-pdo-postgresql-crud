<?php
require_once __DIR__ . "/bootstrap.php";
require_once __DIR__ . "/validator.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$errors = [];

if ($id <= 0) die("ID not provided.");

$customer = $customersRepo->findById($id);
if (!$customer) die("Customer not found.");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  csrf_verify($_POST["csrf"] ?? null);

  $name  = $_POST["name"] ?? "";
  $email = $_POST["email"] ?? "";

  [$nameClean, $emailClean, $errors] = validate_customer_input($name, $email);

  if (!$errors) {
    try {
      $customersRepo->update($id, $nameClean, $emailClean);
      flash_set("success", "Customer updated successfully.");
      redirect("index.php");
    } catch (PDOException $e) {
      if (is_unique_violation($e)) {
        $field = unique_violation_field($e);
        if ($field === "email") {
          $errors["email"] = "This email is already in use.";
        } else {
          $errors["general"] = "This update conflicts with an existing record (duplicate).";
        }
      } else {
        $errors["general"] = "Unexpected error while updating customer.";
      }
    }
  }

  // mantém o que o usuário digitou em caso de erro
  $customer["name"] = trim($name);
  $customer["email"] = trim($email);
}

$title = "Edit Customer";
require __DIR__ . "/partials/header.php";
?>

<div class="header">
  <h1>Edit Customer</h1>
  <a class="btn btn-secondary" href="index.php">Back</a>
</div>

<?php if (!empty($errors["general"])): ?>
  <div class="notice error"><?= e($errors["general"]) ?></div>
<?php endif; ?>

<div class="card">
  <form class="form" method="POST" action="edit.php?id=<?= (int)$id ?>">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

    <div class="field">
      <label for="name">Name *</label>
      <input
        id="name"
        type="text"
        name="name"
        required
        maxlength="100"
        value="<?= e($customer["name"] ?? "") ?>"
      >
      <?php if (!empty($errors["name"])): ?>
        <div class="field-error"><?= e($errors["name"]) ?></div>
      <?php endif; ?>
    </div>

    <div class="field">
      <label for="email">Email</label>
      <input
        id="email"
        type="email"
        name="email"
        maxlength="120"
        value="<?= e($customer["email"] ?? "") ?>"
      >
      <?php if (!empty($errors["email"])): ?>
        <div class="field-error"><?= e($errors["email"]) ?></div>
      <?php endif; ?>
    </div>

    <button class="btn" type="submit">Save</button>
  </form>
</div>

<?php require __DIR__ . "/partials/footer.php"; ?>
