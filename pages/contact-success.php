<h2 class="mb-4">Üzenet elküldve</h2>
<p class="lead">Köszönjük, hogy felvetted velünk a kapcsolatot!</p>

<?php if (isset($_SESSION['last_message'])): ?>
  <div class="card shadow-sm">
    <div class="card-header bg-success text-white">
      Beküldött adatok
    </div>
    <div class="card-body">
      <p><strong>Név:</strong> <?= htmlspecialchars($_SESSION['last_message']['name']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['last_message']['email']) ?></p>
      <p><strong>Üzenet:</strong><br><?= nl2br(htmlspecialchars($_SESSION['last_message']['message'])) ?></p>
    </div>
  </div>
  <?php unset($_SESSION['last_message']); ?>
<?php else: ?>
  <div class="alert alert-warning">Nincs megjeleníthető adat.</div>
<?php endif; ?>
