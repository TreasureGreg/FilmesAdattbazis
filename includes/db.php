<?php
try {
    $dbh = new PDO('pgsql:host=localhost;port=3306;dbname=filmes_adatbazis', 'root', '123456', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Adatbázis hiba: " . $e->getMessage());
}
