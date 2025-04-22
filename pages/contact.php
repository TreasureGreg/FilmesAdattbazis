
<h2 class="mb-4">Kapcsolatfelvétel</h2>

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
