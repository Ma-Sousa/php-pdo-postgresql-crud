<?php
require_once __DIR__ . "/bootstrap.php";
require_once __DIR__ . "/validator.php";

$errors = [];
$name = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  csrf_verify($_POST["csrf"] ?? null);

  $name  = trim($_POST["name"] ?? "");
  $email = trim($_POST["email"] ?? "");
  
  [$nameClean, $emailClean, $errors] = validate_customer_input($name, $email);

  if (!$errors) {
    try {
      createCustomer($pdo, $nameClean, $emailClean);
      flash_set("success", "Customer created successfully.");
      redirect("index.php");
    } catch (PDOException $e) {
      if (is_unique_violation($e)) {
        $field = unique_violation_field($e);
        if ($field === "email") {
          $errors["email"] = "This email is already in use.";
        } else {
          $errors["general"] = "This record conflicts with an existing one (duplicate).";
        } 
      } else {
          $errors["general"] = "Unexpected error while creating customer.";
        }
      }
    }
  }
 
$title = "Create Customer";
require __DIR__ . "/partials/header.php";

?>
<div class="header">
    <h1>Create Customer</h1>
    <a class="btn btn-secondary" href="index.php">Back</a>
</div>

<?php if (!empty($errors["general"])): ?>
    <div class="notice error"><?= e($errors["general"]) ?></div>
<?php endif; ?>

<div class="card">
  <form class="form" method="POST" action="create.php">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

    <div class="field">
      <label for="name">Name *</label>
      <input id="name" type="text" name="name" required maxlength="100" value="<?= e($name) ?>">
      <?php if (!empty($errors["name"])): ?>
        <div class="field-error"><?= e($errors["name"]) ?></div>
      <?php endif; ?>
    </div>

    <div class="field">
      <label for="email">Email</label>
      <input id="email" type="email" name="email" maxlength="120" value="<?= e($email) ?>">
      <?php if (!empty($errors["email"])): ?>
        <div class="field-error"><?= e($errors["email"]) ?></div>
      <?php endif; ?>
    </div>

    <button class="btn" type="submit">Create</button>
  </form>
</div>

<?php require __DIR__ . "/partials/footer.php"; ?>

