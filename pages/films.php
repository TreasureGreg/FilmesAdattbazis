<?php
include('includes/db.php');

$search = $_GET['search'] ?? '';
$films = [];

if ($search) {
    $stmt = $dbh->prepare("SELECT * FROM films WHERE LOWER(title) LIKE LOWER(?) ORDER BY created_at DESC");
    $stmt->execute(['%' . $search . '%']);
} else {
    $stmt = $dbh->query("SELECT * FROM films ORDER BY created_at DESC");
}
$films = $stmt->fetchAll();
?>

<h2 class="mb-4">🎬 Filmjeink</h2>

<form class="row g-3 mb-4" method="get" action="index.php">
  <input type="hidden" name="page" value="films">
  <div class="col-md-9">
    <input type="text" name="search" class="form-control" placeholder="Keresés filmcímre..." value="<?= htmlspecialchars($search) ?>">
  </div>
  <div class="col-md-3 text-end">
    <button type="submit" class="btn btn-primary w-100">🔍 Keresés</button>
  </div>
</form>

<?php if (count($films) === 0): ?>
  <div class="alert alert-info">Nincs találat.</div>
<?php else: ?>
  <div class="row g-4">
    <?php foreach ($films as $film): ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card h-100 shadow-sm">
          <?php if ($film['cover_image']): ?>
            <img src="uploads/films/<?= htmlspecialchars($film['cover_image']) ?>" class="card-img-top" alt="Borítókép">
          <?php else: ?>
            <img src="https://via.placeholder.com/400x250?text=Nincs+borító" class="card-img-top" alt="Nincs borító">
          <?php endif; ?>
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">
              <a href="index.php?page=film&id=<?= $film['id'] ?>" class="text-decoration-none"><?= htmlspecialchars($film['title']) ?></a>
            </h5>
            <p class="card-text small"><?= nl2br(htmlspecialchars($film['description'])) ?></p>
          </div>
          <div class="card-footer text-muted small">
            Feltöltve: <?= date('Y.m.d', strtotime($film['created_at'])) ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
