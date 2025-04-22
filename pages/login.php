<?php include('includes/db.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isLogin = isset($_POST['login']);
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($isLogin) {
        $stmt = $dbh->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = ['id' => $user['id'], 'email' => $user['email'], 'name' => $user['name']];
            header('Location: index.php');
            exit;
        } else {
            $errors[] = "Hibás felhasználónév vagy jelszó.";
        }
    } else {
        $name = $_POST['name'];

        if (strlen($email) < 3 || strlen($password) < 4) {
            $errors[] = "A felhasználónév legalább 3, a jelszó legalább 4 karakter legyen.";
        } else {
            $stmt = $dbh->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = "Ez a felhasználónév már foglalt.";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $dbh->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $hashed]);
                echo "<div class='alert alert-success'>Sikeres regisztráció! Most már bejelentkezhetsz.</div>";
            }
        }
    }
}
?>

<div class="row">
  <div class="col-md-6">
    <h2>Bejelentkezés</h2>
    <?php foreach ($errors as $error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endforeach; ?>

    <form method="post">
      <div class="mb-3">
        <label class="form-label">Felhasználónév</label>
        <input type="text" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Jelszó</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" name="login" class="btn btn-primary">Belépés</button>
    </form>
  </div>

  <div class="col-md-6">
    <h2>Regisztráció</h2>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Teljes név</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Felhasználónév</label>
        <input type="text" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Jelszó</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" name="register" class="btn btn-success">Regisztráció</button>
    </form>
  </div>
</div>
