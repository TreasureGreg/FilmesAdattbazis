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

$stmt = $dbh->prepare("SELECT cover_image FROM films WHERE id = ?");
$stmt->execute([$id]);
$film = $stmt->fetch();

if (!$film) {
    echo "<div class='alert alert-warning'>A film nem található.</div>";
    return;
}

if (!empty($film['cover_image'])) {
    $coverPath = 'uploads/films/' . $film['cover_image'];
    if (file_exists($coverPath)) {
        unlink($coverPath);
    }
}

$stmt = $dbh->prepare("DELETE FROM films WHERE id = ?");
$stmt->execute([$id]);

echo "<div class='alert alert-success'>A film sikeresen törölve lett.</div>";
echo "<a href='index.php?page=films' class='btn btn-primary mt-3'>Vissza a filmekhez</a>";
