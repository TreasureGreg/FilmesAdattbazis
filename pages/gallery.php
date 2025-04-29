<?php
include('includes/db.php');

$stmt = $dbh->query("SELECT id, title, cover_image FROM films WHERE cover_image IS NOT NULL ORDER BY created_at DESC");
$films = $stmt->fetchAll();
?>

<h2 class="mb-4">🎞️ Filmes galéria</h2>

<?php if (count($films) === 0): ?>
  <div class="alert alert-info">Még nincs feltöltött film borítókép.</div>
<?php else: ?>
  <div class="row g-4">
    <?php foreach ($films as $film): ?>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <a href="index.php?page=film&id=<?= $film['id'] ?>" class="text-decoration-none">
          <div class="card shadow-sm h-100 border-0">
            <img src="uploads/films/<?= htmlspecialchars($film['cover_image']) ?>" class="card-img-top rounded" alt="<?= htmlspecialchars($film['title']) ?>">
            <div class="card-body text-center p-2">
              <small class="text-muted"><?= htmlspecialchars($film['title']) ?></small>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
