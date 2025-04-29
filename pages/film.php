<?php
include('includes/db.php');

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo "<div class='alert alert-danger'>Hiányzó vagy érvénytelen azonosító.</div>";
    return;
}

$stmt = $dbh->prepare("SELECT * FROM films WHERE id = ?");
$stmt->execute([$id]);
$film = $stmt->fetch();

if (!$film) {
    echo "<div class='alert alert-warning'>A keresett film nem található.</div>";
    return;
}
?>

<h2 class="mb-4"><?= htmlspecialchars($film['title']) ?></h2>

<div class="row">
  <div class="col-md-5 mb-3">
    <?php if ($film['cover_image']): ?>
      <img src="uploads/films/<?= htmlspecialchars($film['cover_image']) ?>" class="img-fluid rounded shadow" alt="Borítókép">
    <?php else: ?>
      <img src="https://via.placeholder.com/500x300?text=Nincs+borító" class="img-fluid rounded shadow" alt="Nincs borító">
    <?php endif; ?>
  </div>
  <div class="col-md-7">
    <p><strong>Leírás:</strong></p>
    <p><?= nl2br(htmlspecialchars($film['description'])) ?></p>
    <p class="text-muted"><strong>Feltöltve:</strong> <?= date('Y.m.d H:i', strtotime($film['created_at'])) ?></p>
    
    <?php if (isset($_SESSION['user'])): ?>
      <a href="index.php?page=edit-film&id=<?= $film['id'] ?>" class="btn btn-outline-primary me-2">✏️ Szerkesztés</a>
      <a href="index.php?page=delete-film&id=<?= $film['id'] ?>" class="btn btn-outline-danger"
        onclick="return confirm('Biztosan törölni szeretnéd a filmet?')">🗑️ Törlés</a>
    <?php endif; ?>

  </div>
</div>
