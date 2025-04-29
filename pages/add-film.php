<?php
if (!isset($_SESSION['user'])) {
    echo "<div class='alert alert-warning'>Ez az oldal csak bejelentkezett felhasználóknak érhető el.</div>";
    return;
}

include('includes/db.php');

$title = '';
$description = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if (strlen($title) < 2) $errors[] = "A cím túl rövid.";
    if (strlen($description) < 10) $errors[] = "A leírás túl rövid.";

    $coverFilename = null;
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
        $targetDir = 'uploads/films/';
        $filename = time() . '_' . basename($_FILES['cover']['name']);
        $targetPath = $targetDir . $filename;

        $imageFileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
        $validTypes = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($imageFileType, $validTypes)) {
            if (move_uploaded_file($_FILES['cover']['tmp_name'], $targetPath)) {
                $coverFilename = $filename;
            } else {
                $errors[] = "Hiba történt a borítókép feltöltésekor.";
            }
        } else {
            $errors[] = "A borítókép csak JPG, JPEG, PNG vagy WEBP lehet.";
        }
    }

    if (empty($errors)) {
        $stmt = $dbh->prepare("INSERT INTO films (title, description, cover_image) VALUES (?, ?, ?)");
        $stmt->execute([$title, $description, $coverFilename]);
        echo "<div class='alert alert-success'>Film sikeresen hozzáadva!</div>";
        $title = '';
        $description = '';
    }
}
?>

<h2 class="mb-4">Új film hozzáadása</h2>

<?php foreach ($errors as $error): ?>
  <div class="alert alert-danger"><?= $error ?></div>
<?php endforeach; ?>

<form method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <label class="form-label">Film címe</label>
    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($title) ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Leírás</label>
    <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($description) ?></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Borítókép (opcionális)</label>
    <input type="file" name="cover" class="form-control" accept="image/*">
  </div>
  <button type="submit" class="btn btn-success">Film hozzáadása</button>
</form>
