<?php
session_start();

$menu = include('config/menu.php');

$page = $_GET['page'] ?? 'home';

$pagePath = "pages/{$page}.php";

include('includes/header.php');

if (file_exists($pagePath)) {
    include($pagePath);
} else {
    echo "<h2>Az oldal nem található.</h2>";
}

include('includes/footer.php');
