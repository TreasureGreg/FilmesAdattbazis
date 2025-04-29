<?php include('includes/db.php');

$name = '';
$email = '';
$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (strlen($name) < 2) $errors[] = "A név túl rövid.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Érvénytelen email.";
    if (strlen($message) < 5) $errors[] = "Az üzenet túl rövid.";

    if (empty($errors)) {
        $stmt = $dbh->prepare("INSERT INTO messages (name, email, message, user_id) VALUES (?, ?, ?, ?)");
        $userId = $_SESSION['user']['id'] ?? null;
        $stmt->execute([$name, $email, $message, $userId]);
        $_SESSION['last_message'] = ['name' => $name, 'email' => $email, 'message' => $message];
        header("Location: index.php?page=contact-success");
        exit;
    }
}
?>

<h2 class="mb-4">Kapcsolatfelvétel</h2>

<?php foreach ($errors as $error): ?>
  <div class="alert alert-danger"><?= $error ?></div>
<?php endforeach; ?>

<!-- űrlap eleje -->
<form method="post" id="contactForm" novalidate onsubmit="return validateContactForm();">
  <div class="mb-3">
    <label for="name" class="form-label">Név</label>
    <input type="text" name="name" id="name" class="form-control" required value="<?= htmlspecialchars($name) ?>">
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" name="email" id="email" class="form-control" required value="<?= htmlspecialchars($email) ?>">
  </div>
  <div class="mb-3">
    <label for="message" class="form-label">Üzenet</label>
    <textarea name="message" id="message" class="form-control" rows="5" required><?= htmlspecialchars($message) ?></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Üzenet küldése</button>
</form>

<!-- JavaScript validáció -->
<script>
function validateContactForm() {
  const name = document.getElementById('name').value.trim();
  const email = document.getElementById('email').value.trim();
  const message = document.getElementById('message').value.trim();

  if (name.length < 2) {
    alert('A név túl rövid (legalább 2 karakter).');
    return false;
  }

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
  alert('Érvénytelen email cím.');
  return false;
}


  if (message.length < 5) {
    alert('Az üzenet túl rövid (legalább 5 karakter).');
    return false;
  }

  return true;
}
</script>
