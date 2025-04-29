<?php
if (!isset($_SESSION['user'])) {
    echo "<div class='alert alert-warning'>Ez a menüpont csak bejelentkezett felhasználóknak érhető el.</div>";
    return;
}

include('includes/db.php');

$stmt = $dbh->query("SELECT m.*, u.name AS user_name 
                     FROM messages m 
                     LEFT JOIN users u ON m.user_id = u.id 
                     ORDER BY created_at DESC");
$messages = $stmt->fetchAll();
?>

<h2 class="mb-4">Beérkezett üzenetek</h2>

<?php if (count($messages) === 0): ?>
  <div class="alert alert-info">Még nem érkezett üzenet.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>Dátum</th>
          <th>Név</th>
          <th>Email</th>
          <th>Üzenet</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($messages as $m): ?>
          <tr>
            <td><?= date('Y.m.d. H:i', strtotime($m['created_at'])) ?></td>
            <td><?= $m['user_name'] ?? htmlspecialchars($m['name']) . ' (Vendég)' ?></td>
            <td><?= htmlspecialchars($m['email']) ?></td>
            <td><?= nl2br(htmlspecialchars($m['message'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
