<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <title>Filmes adatbázis</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body class="d-flex flex-column min-vh-100">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">🎬 Filmes adatbázis</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <?php
          $currentPage = $_GET['page'] ?? 'home';

          foreach ($menu as $key => $value) {
            if ($key === 'login' && isset($_SESSION['user'])) continue;
            if ($key === 'logout' && !isset($_SESSION['user'])) continue;

            $active = $key === $currentPage ? 'active' : '';
            echo "<li class='nav-item'>
                    <a class='nav-link $active' href='index.php?page=$key'>$value</a>
                  </li>";
          }
          ?>
      </ul>

      </div>
    </div>
  </nav>

  <div class="container flex-grow-1">
    <?php if (isset($_SESSION['user'])): ?>
      <div class="alert alert-success text-end" role="alert">
        Bejelentkezett: <strong><?= $_SESSION['user']['name'] ?></strong> (<?= $_SESSION['user']['email'] ?>)
      </div>
    <?php endif; ?>