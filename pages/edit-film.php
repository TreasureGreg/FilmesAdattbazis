<?php
if (!isset($_SESSION['user'])) {
    echo "<div class='alert alert-warning'>Ez az oldal csak bejelentkezett felhasználóknak érhető el.</div>";
    return;
}

include('includes/db.php');

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    echo "<div class='alert alert-danger'>Érvénytelen film azonosító.</div>";
    return;
}

$stmt = $dbh->prepare("SELECT * FROM films WHERE id = ?");
$stmt->execute([$id]);
$film = $stmt->fetch();

if (!$film) {
    echo "<div class='alert alert-warning'>A film nem található.</div>";
    return;
}

$title = $film['title'];
$description = $film['description'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if (strlen($title) < 2) $errors[] = "A cím túl rövid.";
    if (strlen($description) < 10) $errors[] = "A leírás túl rövid.";

    $coverFilename = $film['cover_image'];

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
        $stmt = $dbh->prepare("UPDATE films SET title = ?, description = ?, cover_image = ? WHERE id = ?");
        $stmt->execute([$title, $description, $coverFilename, $id]);
        echo "<div class='alert alert-success'>A film adatai frissítve lettek!</div>";
        $film['cover_image'] = $coverFilename;
    }
}
?>

<h2 class="mb-4">Film szerkesztése</h2>

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
    <label class="form-label">Borítókép (új feltöltés opcionális)</label>
    <input type="file" name="cover" class="form-control" accept="image/*">
    <?php if ($film['cover_image']): ?>
      <img src="uploads/films/<?= htmlspecialchars($film['cover_image']) ?>" class="img-fluid mt-2 rounded" style="max-height: 200px;">
    <?php endif; ?>
  </div>
  <button type="submit" class="btn btn-primary">Mentés</button>
  <a href="index.php?page=film&id=<?= $film['id'] ?>" class="btn btn-outline-secondary">Vissza</a>
</form>
