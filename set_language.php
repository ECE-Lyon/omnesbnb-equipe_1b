<?php
if (isset($_GET['lang'])) {
    setcookie('lang', $_GET['lang'], time() + 3600 * 24 * 30, "/"); // Cookie valable 30 jours
}
// Rediriger vers la page précédente si possible, sinon vers compte.php
$redirect = $_SERVER['HTTP_REFERER'] ?? 'Accueil/index.php';
header("Location: $redirect");

exit();
